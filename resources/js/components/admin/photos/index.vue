<template>
    <div>
        <v-card v-scroll="onScroll">
            <v-toolbar>
                <v-toolbar-title>
                    {{ $t('photos.header')}}
                </v-toolbar-title>

                <v-divider class="mx-4" vertical inset></v-divider>

                <v-spacer></v-spacer>

                <v-text-field v-model="search.keyword"
                              @keyup.enter="onSearch"
                              append-icon="search"
                              label="Поиск"
                              single-line></v-text-field>

                <v-spacer></v-spacer>

                <!-- TODO необходимо что-то сделать с z-index у календаря -->
                <!--<v-text-field-->
                <!--@click="calendar = true"-->
                <!--v-model="search.created_at"-->
                <!--label="Дата создания"-->
                <!--prepend-icon="event"-->
                <!--dense-->
                <!--readonly-->
                <!--hide-details-->

                <!--&gt;</v-text-field>-->

                <!--<v-date-picker-->
                <!--:locale="$i18n.locale"-->
                <!--width="550"-->
                <!--:style="{position: 'absolute', right: '10%', top: '100%', 'z-index': 3}"-->
                <!--no-title-->
                <!--scrollable-->
                <!--first-day-of-week="1"-->
                <!--color="primary"-->
                <!--v-if="calendar"-->
                <!--v-model="search.created_at">-->

                <!--<v-spacer></v-spacer>-->

                <!--<v-btn color="blue darken-1" @click="calendar = false" text>-->
                <!--{{ $t('users.btn.cancel') }}-->
                <!--</v-btn>-->

                <!--<v-btn color="primary" outlined @click="calendar = false">-->
                <!--OK-->
                <!--</v-btn>-->
                <!--</v-date-picker>-->

                <!--<v-spacer></v-spacer>-->

                <v-dialog v-model="modal" max-width="500px">
                    <template v-slot:activator="{on}">
                        <v-btn color="success" outlined v-on="on">
                            <i class="pe-7s-plus"></i>
                            {{ $t('photos.btn.add')}}
                        </v-btn>
                    </template>

                    <v-card>
                        <v-form  ref="form" v-model="form.valid">
                        <v-card-title>
                            {{ $t('photos.form.header')}}
                        </v-card-title>
                        <v-card-text>
                            <v-text-field v-model="photo.title"
                                          :label="$t('photos.form.labels.title')"
                                          clearable
                                          :rules="form.title.rules"
                                          counter
                                          maxlength="100">

                            </v-text-field>

                            <vue-dropzone ref="photo_dropzone"
                                          id="dropzone"
                                          :options="dropzone_options"
                                          @vdropzone-success="onDropzoneSuccess"
                                          @vdropzone-error="onDropzoneError"
                                          @vdropzone-removed-file="onDropzoneRemoved"></vue-dropzone>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>

                            <v-btn color="success" :disabled="! form.valid || ! photo.path.length" @click="save()">
                                {{ $t('photos.btn.save')}}
                            </v-btn>
                            <v-btn color="blue darken-1" text @click="cancel()">
                                {{ $t('photos.btn.cancel')}}
                            </v-btn>
                        </v-card-actions>
                        </v-form>
                    </v-card>
                </v-dialog>
            </v-toolbar>

            <v-card-text>
                <v-row v-show="! loading">
                    <v-col cols="4" v-for="(photo, index) in photos" :key="photo.id">
                        <v-card>
                            <v-card-title>
                                {{ photo.title }}
                            </v-card-title>
                            <v-card-text class="m-b-md">
                                <v-img :src="photo.path" alt="" class="m-b-md"></v-img>
                            </v-card-text>

                            <v-card-actions class="mt-auto">
                                <v-btn color="error" outlined @click="remove(photo.id)">
                                    {{ $t('photos.btn.delete')}}
                                </v-btn>
                                <v-btn color="warning" outlined @click="$router.push('/admin/photos/' + photo.id)">
                                    {{ $t('photos.btn.edit')}}
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </v-row>

                <v-skeleton-loader type="card" v-show="loading"></v-skeleton-loader>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
  import vue2Dropzone from 'vue2-dropzone';
  import 'vue2-dropzone/dist/vue2Dropzone.min.css';
  import dropzone_options from "../../../mixins/dropzone_options";

  import debounce from '../../../debounce';
  import scroll from "../../../mixins/infinite_scroll";

  export default {
    name: "index",

    mixins: [dropzone_options, scroll],

    components: {
      vueDropzone: vue2Dropzone
    },

    data() {
      return {
        photos: [],

        searching: false,

        loading: false,

        search: {
          keyword: '',
          created_at: null
        },

        photo: {
          title: '',
          path: ''
        },

        form: {
          valid: false,
          title: {
            rules: [
              v => v !== '' || this.$t('photos.form.rules.title.required'),
              v => (v !== undefined && v !== null && v.length <= 255) || this.$t('photos.form.rules.title.length', { length: 255})
            ]
          },
        },

        pagination: {
          page: 1,
          last_page: 1
        },

        modal: false,
        calendar: false,

        isDestroying: false
      }
    },

    methods: {
      async loadData() {
        this.loading = true;
        const response = await axios.get(`${this.$attrs.apiRoute}/photos`, {
          params: {
            page: this.pagination.page
          }
        });

        if (response.data.status === 'error') {
          this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
          this.loading = false;
          return false;
        }

        this.photos = (this.pagination.page === 1)
            ? response.data.photos.data
            : this.photos.concat(response.data.photos.data);

        this.pagination.last_page = response.data.photos.last_page;
        this.loading = false;
      },

      onSearch() {
        if (this.search.keyword.length < 3) {
          return ;
        }

        this.searchData(this);
      },

      searchData: debounce((vm) => {
        vm.loading = true;
        axios.get(`${this.$attrs.apiRoute}/photos`, {
          params: {
            page: vm.pagination.page,
            ...vm.search,
          }
        })
            .then(response => {
              vm.photos = (vm.pagination.page === 1)
                  ? response.data.photos.data
                  : vm.photos.concat(response.data.photos.data);

              vm.pagination.last_page = response.data.photos.last_page;
              vm.loading = false;
            })
            .catch(error => {
              vm.loading = false;
              console.log(error);
              vm.$swal(vm.$t('swal.title.error'), error.data.msg, 'error');
            })
        ;
      }, 300),

      async remove(id) {
        const response = await axios.delete(`${this.$attrs.apiRoute}/admin/photos/${id}`);

        switch (response.data.status) {
          case 'error':
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            return false;

          case 'success':
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            this.loadData();
        }
      },

      async save() {
        this.modal = false;

        const response = await axios.post(`${this.$attrs.apiRoute}/photos`, this.photo);

        switch (response.status) {
          case 200:
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            this.loadData();
            break;

          case 500:
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            break;
        }


        this.resetForm();
      },

      cancel() {
        this.modal = false;

        this.resetForm();
      },

      async resetForm() {
        this.$refs.form.reset();
        this.isDestroying = true;
        await this.$refs.photo_dropzone.removeAllFiles();
        this.$refs.photo_dropzone.enable();
        this.isDestroying = false;
      },

      initializeDropzone() {
        this.dropzone_options.url = `${this.$attrs.apiRoute}/photos/upload`;
        this.dropzone_options.dictDefaultMessage = this.$t('dropzone.dictDefaultMessage.images');
        this.dropzone_options.acceptedFiles = '.jpeg,.jpg,.png';
      },

      onDropzoneSuccess(file, response) {
        this.photo.path = response.tmp_path;
        this.$refs.photo_dropzone.disable();
      },

      onDropzoneError(file, response, xhr) {
        console.log(response.data);
      },

      async onDropzoneRemoved(file, error, xhr) {
        if  (this.isDestroying) {
          return ;
        }

        console.log(file, error, xhr);
        if (error) {
          console.error(error);
          return;
        }

        const response = await axios.post(`${this.$attrs.apiRoute}/photos/remove_tmp_photo`, {path: this.photo.path});

        this.$refs.photo_dropzone.enable();

        switch (response.status) {
          case 200:
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            break;

          case 500:
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            break;
        }
      },

      onScroll: function() {
        this.paginationScroll(this, document);
      },

      async initData() {
        await this.loadData();
      }
    },

    watch: {
      'search': {
        handler: function(after, before) {
          if (after.category || after.created_at || after.keyword.length > 3) {
            this.pagination.page = 1;
            this.searching = true;

            this.searchData(this);
          } else if (! after.category && ! after.created_at && ! after.keyword.length) {
            this.pagination.page = 1;
            this.searching = false;

            this.loadData();
          }
        },

        deep: true
      },

      'search.keyword': function (after, before) {
        if (after.length > 3) {
          this.onSearch();
        }
      },
    },

    created() {
      this.initializeDropzone(
          '/admin/photos/upload',
          this.$t('dropzone.dictDefaultMessage.images'),
          '.jpg,.jpeg,.png'
      );

      this.initData();
    },

    mounted() {
      document.addEventListener('scroll', this.onScroll);
    },

    beforeDestroy() {
      this.isDestroying = true;
    }
  }
</script>

<style scoped>

</style>
