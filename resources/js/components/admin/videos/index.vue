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

                <v-menu ref="search-calendar-menu"
                        v-model="calendar"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        max-width="290px"
                        min-width="200px">
                    <template v-slot:activator="{ on }">
                        <v-text-field
                                v-on="on"
                                v-model="search.display_created_at"
                                label="Дата загрузки"
                                prepend-icon="event"
                                hint="Дата загрузки"
                                dense
                                readonly
                                clearable
                                persistent-hint
                                single-line
                        ></v-text-field>
                    </template>

                    <v-date-picker
                            :locale="$i18n.locale"
                            width="290"
                            no-title
                            scrollable
                            first-day-of-week="1"
                            color="primary"
                            @input="calendar = false"
                            v-model="search.created_at">

                        <v-spacer></v-spacer>

                        <v-btn color="blue darken-1" @click="calendar = false" text>
                            {{ $t('users.btn.cancel') }}
                        </v-btn>

                        <v-btn color="primary" outlined @click="calendar = false">
                            OK
                        </v-btn>
                    </v-date-picker>
                </v-menu>

                <v-spacer></v-spacer>


                <v-dialog v-model="modal" max-width="500px" persistent>
                    <template v-slot:activator="{on}">
                        <v-btn color="success"
                               outlined
                               v-on="on"
                               @click="resetPreview = false">
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
                                              maxlength="100"
                                              :rules="form.title.rules">

                                </v-text-field>

                                <preview-upload @uploaded="onUpload" ref="previewUpload"
                                                :extensions="extensions.join(',')"
                                                type="video"
                                                :reset="resetPreview">
                                </preview-upload>

                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>

                                <v-btn color="success"
                                       outlined
                                       :disabled="!form.valid || ! video.video"
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
                <v-row v-show="! loading && videos.length">
                    <v-col col="6" v-for="(video, index) in videos" :key="video.id" height="100%" class="flex-card-full-size">
                        <v-card height="100%" class="flex-card-full-size">
                            <v-card-title>
                                {{ video.title }}
                            </v-card-title>
                            <v-card-text class="m-b-md">
                                <video :src="video.src" controls height="240" width="320"></video>
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
                    <!--<v-pagination :length="pagination.last_page"-->
                                  <!--v-model="pagination.page"-->
                                  <!--:total-visible="7"-->
                                  <!--circle></v-pagination>-->
                </v-row>

                <v-skeleton-loader type="card" v-show="loading"></v-skeleton-loader>

                <v-alert color="info" outlined v-if="! loading && ! videos.length">
                    <div class="">
                        <span v-show="! searching">
                            Видеоролики отсутствуют в системе
                        </span>
                        <span v-show="searching">
                            По Вашему запросу ничего не найдено
                        </span>
                    </div>
                </v-alert>
            </v-card-text>


        </v-card>
    </div>
</template>

<script>
  import previewUpload from '../../../custom_components/previewUploader';

  import infinite_scroll_mixin from '../../../mixins/infinite_scroll';
  import debounce from '../../../debounce';

  export default {
    name: "index",

    mixins: [infinite_scroll_mixin],
    components: {
      previewUpload
    },

    data() {
      return {
        videos: [],

        extensions: ['.mp4', '.mpeg', '.mpg'],

        searching: false,

        video: {
          title: '',
          video: null
        },

        form: {
          valid: false,
          title: {
            rules: [
                v => v !== '' || this.$t('videos.form.rules.title.required'),
                v => (v !== undefined && v !== null && v.length <= 100) || this.$t('videos.form.rules.title.length', { length: 255})
            ]
          },
        },

        search: {
          keyword: '',
          created_at: null,
          display_created_at: new Date().toLocaleString('ru', {year: 'numeric', month: 'numeric', day: 'numeric', timezone: 'utc'})
        },

        pagination: {
          last_page: 1,
          page: 1
        },

        modal: false,
        calendar: false,

        isDestroying: false,
        loading: false,
        resetPreview: false
      }
    },

    methods: {
      onUpload(video) {
        this.video.video = video;
      },

      /**
       * Загрузка списка видео
       *
       */
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

      /**
       *  Удаление видео
       *
       */
      async remove(id) {
        const response = await axios.delete(`${this.$attrs.apiRoute}/videos/${id}`);

        switch (response.data.status) {
          case 'error':
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            return false;

          case 'success':
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            this.videos = this.videos.filter(video => video.id !== id);
            break;
        }
      },

      /**
       * Обработчик события для debounce-поиска
       */
      onSearch() {
        if (this.search.keyword.length < 3) {
          return ;
        }

        this.searchData(this);
      },

      /**
       * Поиск видео
       *
       */
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

      /**
       * Сохранение видео
       *
       */
      async save() {
        const formData = new FormData();

        for (const prop in this.video) {
          formData.append(prop, this.video[prop]);
        }

        const response = await axios.post(
            `${this.$attrs.apiRoute}/videos`,
            formData,
            {
              headers: {
                'Content-Type': 'multipart/form-data'
              }
            }
        );

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

      /**
       * Закрытие модального окна
       *
       */
      cancel() {
        this.resetForm();
      },

      /**
       * Сброс формы в модальном окне
       *
       */
      async resetForm() {
        this.modal = false;
        this.resetPreview = true;
        this.video.video = null;
        this.$refs.form.reset();
      },

      async initData() {
        await this.loadData();
      }
    },

    watch: {
      'search': {
        handler: function(after, before) {
          if (after.created_at || after.keyword.length > 3) {
            this.pagination.page = 1;
            this.searching = true;

            this.searchData(this);
          } else if (! after.created_at && ! after.keyword.length) {
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

      'search.created_at': function (after) {
        if (after !== null) {
          this.search.display_created_at = after.split('-').reverse().join('.');
        }
      },

      'search.display_created_at': function (after) {
        if (after === null) {
          this.search.created_at = null;
        }
      }
    },

    created() {
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
    .flex-card-full-size {
        display: flex;
        flex-direction: column;
    }
</style>
