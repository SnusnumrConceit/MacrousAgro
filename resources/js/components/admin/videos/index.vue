<template>
    <div>
        <v-card>
            <v-toolbar>
                <v-toolbar-title>
                    {{ $t('videos.header')}}
                </v-toolbar-title>

                <v-divider class="mx-4" vertical inset></v-divider>
                <v-spacer></v-spacer>

                <v-text-field
                        v-model="search.keyword"
                        @keyup.enter="onSearch"
                        append-icon="search"
                        label="Поиск"
                        single-line>
                </v-text-field>

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
                        <v-btn color="success" v-on="on" outlined>
                            <i class="pe-7s-plus"></i>
                            {{ $t('videos.btn.add')}}
                        </v-btn>
                    </template>
                    <v-card>
                        <v-form v-model="form.valid" ref="form">
                            <v-card-title>
                                Видео
                            </v-card-title>
                            <v-card-text>
                                <v-text-field v-model="video.title"
                                              :label="$t('videos.form.labels.title')"
                                              counter
                                              clearable
                                              maxlength="255"
                                              :rules="form.title.rules">

                                </v-text-field>

                                <vue-dropzone ref="video_dropzone"
                                              id="dropzone"
                                              :options="dropzone_options"
                                              @vdropzone-success="onDropzoneSuccess"
                                              @vdropzone-error="onDropzoneError"
                                              @vdropzone-removed-file="onDropzoneRemoved">
                                </vue-dropzone>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>

                                <v-btn color="success"
                                       outlined
                                       :disabled="!form.valid || ! video.path.length"
                                       @click="save()">
                                    {{ $t('videos.btn.save')}}
                                </v-btn>
                                <v-btn color="blue darken-1" @click="cancel()" text>
                                    {{ $t('videos.btn.cancel')}}
                                </v-btn>
                            </v-card-actions>

                        </v-form>
                    </v-card>
                </v-dialog>
            </v-toolbar>

            <v-card-text>
                <v-row v-show="! loading">
                    <v-col col="6" v-for="(video, index) in videos" :key="video.id">
                        <v-card>
                            <v-card-title>
                                {{ video.title }}
                            </v-card-title>
                            <v-card-text>
                                <video :src="video.path" controls></video>
                            </v-card-text>
                            <v-card-actions>
                                <v-btn color="error" outlined @click="remove(video.id)">
                                    {{ $t('videos.btn.delete' )}}
                                </v-btn>
                                <v-btn color="warning" outlined @click="$router.push('/admin/videos/' + video.id)">
                                    {{ $t('videos.btn.edit' )}}
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                    <v-pagination :length="pagination.last_page"
                                  v-model="pagination.page"
                                  :total-visible="7"
                                  circle></v-pagination>
                </v-row>

                <v-skeleton-loader type="card" v-show="loading"></v-skeleton-loader>
            </v-card-text>


        </v-card>
    </div>
</template>

<script>
  import vue2Dropzone from 'vue2-dropzone';
  import 'vue2-dropzone/dist/vue2Dropzone.min.css';
  import dropzone_mixin from '../../../mixins/dropzone_options';

  import infinite_scroll_mixin from '../../../mixins/infinite_scroll';
  import debounce from '../../../debounce';

  export default {
    name: "index",

    mixins: [dropzone_mixin, infinite_scroll_mixin],
    components: {
      vueDropzone: vue2Dropzone
    },

    data() {
      return {
        videos: [],

        searching: false,

        video: {
          title: '',
          path: ''
        },

        form: {
          valid: false,
          title: {
            rules: [
                v => v !== '' || this.$t('videos.form.rules.title.required'),
                v => (v !== undefined && v !== null && v.length <= 255) || this.$t('videos.form.rules.title.length', { length: 255})
            ]
          },
        },

        search: {
          keyword: ''
        },

        pagination: {
          last_page: 1,
          page: 1
        },

        modal: false,
        calendar: false,

        isDestroying: false,
        loading: false,
      }
    },

    methods: {
      async loadData() {
        this.loading = true;
        const response = await axios.get(`${this.$attrs.apiRoute}/videos`, {
          params: {
            page: this.page
          }
        });

        if (response.data.status === 'error') {
          this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
          this.loading = false;
          return false;
        }

        this.videos = (this.pagination.page === 1)
            ? response.data.videos.data
            : this.videos[response.data.videos.data];

        this.pagination.last_page = response.data.videos.last_page;

        this.loading = false;
      },

      async remove(id) {
        const response = await axios.delete(`${this.$attrs.apiRoute}/videos/${id}`);

        switch (response.data.status) {
          case 'error':
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            return false;

          case 'success':
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            this.loadData();
        }
      },

      onSearch() {
        if (this.search.keyword.length < 3) {
          return ;
        }

        this.searchData(this);
      },

      searchData: debounce((vm) => {
        vm.loading = true;
        axios.get(`${vm.$attrs.apiRoute}/videos`, {
          params: {
            page: vm.pagination.page,
            ...vm.search,
          }
        })
        .then(response => {
          vm.videos = response.data.videos.data;
          vm.pagination.last_page = response.data.videos.last_page;
          vm.loading = false;
        })
        .catch(error => {
          vm.$swal(vm.$t('swal,title.error'), error.data.msg, 'error');
          vm.loading = false;
        })
        ;
      }, 300),

      async save() {
        this.modal = false;

        const response = await axios.post(`${this.$attrs.apiRoute}/videos`, this.video);

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
        await this.$refs.video_dropzone.removeAllFiles();
        this.$refs.video_dropzone.enable();
        this.isDestroying = false;
      },

      initializeDropzone() {
        this.dropzone_options.url = `${this.$attrs.apiRoute}/videos/upload`;
        this.dropzone_options.dictDefaultMessage = this.$t('dropzone.dictDefaultMessage.videos');
        this.dropzone_options.acceptedFiles = '.mp4,.mpg,mpeg';
      },

      onDropzoneSuccess(file, response) {
        this.video.path = response.tmp_path;
        this.$refs.video_dropzone.disable();
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

        const response = await axios.post(`${this.$attrs.apiRoute}/videos/remove_tmp_video`, {path: this.video.path});

        this.$refs.video_dropzone.enable();

        switch (response.status) {
          case 200:
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            break;

          case 500:
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            break;
        }
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
      }
    },

    created() {
      this.initializeDropzone(
          `${this.$attrs.apiRoute}/videos/upload`,
          this.$t('dropzone.dictDefaultMessage.videos'),
          '.mp4,.mpg,.mpeg'
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
