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

                <v-dialog v-model="modal" max-width="500px">
                    <template v-slot:activator="{on}">
                        <v-btn color="success" outlined v-on="on">
                            <i class="pe-7s-plus"></i>
                            {{ $t('photos.btn.add')}}
                        </v-btn>
                    </template>

                    <v-card>
                        <v-card-title>
                            {{ $t('photos.form.header')}}
                        </v-card-title>
                        <v-card-text>
                            <v-text-field v-model="photo.title"
                                          :label="$t('photos.form.labels.title')"
                                          clearable
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

                            <v-btn color="success" :disabled="! photo.path.length" @click="save()">
                                {{ $t('photos.btn.save')}}
                            </v-btn>
                            <v-btn color="blue darken-1" text @click="cancel()">
                                {{ $t('photos.btn.cancel')}}
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-toolbar>

            <v-card-text>
                <v-row>
                    <v-col cols="4" v-for="(photo, index) in photos" :key="photo.id">
                        <v-card>
                            <v-card-title>
                                {{ photo.title }}
                            </v-card-title>
                            <v-card-text>
                                <v-img :src="photo.path" alt=""></v-img>
                            </v-card-text>
                            <v-card-actions>
                                <v-btn color="error" outlined @click="remove(photo.id)">
                                    {{ $t('photos.btn.delete')}}
                                </v-btn>
                                <v-btn color="warning" outlined @click="$router.push('/admin/photos/' + photo.id)">
                                    {{ $t('photos.btn.edit')}}
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                    <!--<v-pagination :length="pagination.last_page"-->
                                  <!--circle-->
                                  <!--v-model="pagination.page"-->
                                  <!--:total-visible="7"></v-pagination>-->
                </v-row>
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

        is_search: false,

        search: {
          keyword: ''
        },

        photo: {
          title: '',
          path: ''
        },

        pagination: {
          page: 1,
          last_page: 1
        },

        modal: false
      }
    },

    methods: {
      async loadData() {
        const response = await axios.get('/admin/photos', {
          params: {
            page: this.pagination.page
          }
        });

        if (response.data.status === 'error') {
          this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
          return false;
        }

        this.photos = (this.pagination.page === 1)
            ? response.data.photos.data
            : this.photos.concat(response.data.photos.data);

        this.pagination.last_page = response.data.photos.last_page;
      },

      onSearch() {
        if (this.search.keyword.length < 3) {
          return ;
        }

        this.searchData(this);
      },

      searchData: debounce((vm) => {
        axios.get('/admin/photos', {
          params: {
            page: vm.pagination.page,
            keyword: vm.search.keyword
          }
        })
            .then(response => {
              vm.photos = response.data.photos.data;
              vm.pagination.last_page = response.data.photos.last_page;
            })
            .catch(error => {
              console.log(error);
              vm.$swal(vm.$t('swal.title.error'), error.data.msg, 'error');
            })
        ;
      }, 300),

      async remove(id) {
        const response = await axios.delete(`/admin/photos/${id}`);

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

        const response = await axios.post('/admin/photos', this.photo);

        switch (response.status) {
          case 200:
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            this.loadData();
            break;

          case 500:
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            break;
        }


        this.$refs.form.reset();
      },

      cancel() {
        this.modal = false;
        this.$refs.form.reset();
      },

      initializeDropzone() {
        this.dropzone_options.url = '/admin/photos/upload';
        this.dropzone_options.dictDefaultMessage = this.$t('dropzone.dictDefaultMessage.videos');
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
        console.log(file, error, xhr);
        if (error) {
          console.error(error);
          return;
        }

        const response = await axios.post('/admin/photos/remove_tmp_photo', {path: this.photo.path});

        this.$refs.photo_dropzone.enable();

        switch (response.status) {
          case 200:
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            break;

          case 500:
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            break;
        }
      }
    },

    watch: {
      'search.keyword': function (after, before) {
        if (after.length > 3) {
          this.onSearch();
        }
      }
    },

    created() {
      this.loadData();

      this.initializeDropzone(
          '/admin/photos/upload',
          this.$t('dropzone.dictDefaultMessage.photos'),
          '.jpg,.jpeg,.png'
      );
    },

    mounted() {
      document.addEventListener('scroll', this.onScroll);
    }
  }
</script>

<style scoped>

</style>
