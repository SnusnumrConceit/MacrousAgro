<template>
    <div>
        <v-card v-scroll="onScroll">
            <!-- TODO вынести в компонент photos-search -->
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
                            @input="calendar = false"
                            :locale="$i18n.locale"
                            width="290"
                            no-title
                            scrollable
                            first-day-of-week="1"
                            color="primary"
                            v-if="calendar"
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

                <!-- TODO вынести в компонент photos-create-form -->

                <v-dialog v-model="modal" max-width="500px" persistent>
                    <template v-slot:activator="{on}">
                        <v-btn color="success" outlined v-on="on" @click="resetImage = false">
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
                            <errors :errors="errors"></errors>

                            <v-text-field v-model="photo.title"
                                          :label="$t('photos.form.labels.title')"
                                          clearable
                                          :rules="form.title.rules"
                                          counter
                                          maxlength="100">

                            </v-text-field>

                            <preview-upload @uploaded="onUploadImage" :reset="resetPreview"></preview-upload>

                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>

                            <v-btn color="success" :disabled="! form.valid || ! photo.image" @click="save()">
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
                <!-- TODO вынести в компонент photos-list -->
                <v-row v-show="! loading && photos.length">
                    <v-col cols="4" v-for="photo in photos" :key="photo.id">
                        <v-card height="100%" class="flex-card-full-size">
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

                <v-alert color="info" outlined v-if="! loading && ! photos.length">
                    <div class="">
                        <span v-show="! searching">
                            Фотографии отсутствуют в фотогалерее
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

  import debounce from '../../../debounce';
  import scroll from "../../../mixins/infinite_scroll";

  export default {
    name: "index",

    mixins: [scroll],

    components: {
      previewUpload
    },

    data() {
      return {
        photos: [],

        search: {
          keyword: '',
          created_at: null,
          display_created_at: new Date().toLocaleString('ru', {year: 'numeric', month: 'numeric', day: 'numeric', timezone: 'utc'})
        },

        photo: {
          title: '',
          image: null,
          src: ''
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

        searching: false,
        resetPreview: false,
        loading: false,

        errors: []
      }
    },

    methods: {
      /**
       * Обработчик события в форме добавления - отслеживает загрузку фотографии
       * @param image
       */
      onUploadImage(image) {
        this.photo.image = image;
      },

      /**
       * Загрузка списка фотографий
       *
       * @returns {Promise<void>}
       */
      async getPhotos() {
        this.loading = true;

        try {
          const response = await axios.get(`${this.$attrs.apiRoute}/photos`, {
            params: {
              page: this.pagination.page
            }
          });

          this.photos = (this.pagination.page === 1)
              ? response.data.photos.data
              : this.photos.concat(response.data.photos.data);

          this.pagination.last_page = response.data.photos.last_page;
          this.loading = false;
        } catch (e) {
          this.$swal(this.$t('swal.title.error'), e.response.data.msg, 'error');
          this.loading = false;
        }
      },

      /**
       * Обработчик события для debounce-поиска фотографий
       *
       */
      onSearch() {
        if (this.search.keyword.length < 3) {
          return ;
        }

        this.searchData(this);
      },

      /**
       * Поиск фотографий
       */
      searchData: debounce((vm) => {
        vm.loading = true;
        axios.get(`${vm.$attrs.apiRoute}/photos`, {
          params: {
            page: vm.pagination.page,
            ...vm.search,
          }
        }).then(response => {
          vm.photos = (vm.pagination.page === 1)
              ? response.data.photos.data
              : vm.photos.concat(response.data.photos.data);

          vm.pagination.last_page = response.data.photos.last_page;
          vm.loading = false;
        }).catch(error => {
          vm.loading = false;
          console.log(error);
          vm.$swal(vm.$t('swal.title.error'), error.data.msg, 'error');
        })
        ;
      }, 300),

      /**
       * Удаление фотографии из списка
       *
       * @param id
       * @returns {Promise<void>}
       */
      async remove(id) {
        try {
          const response = await axios.delete(`${this.$attrs.apiRoute}/photos/${id}`);
          this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
          this.photos = this.photos.filter(photo => photo.id !== id);
        } catch (e) {
          this.$swal(this.$t('swal.title.error'), e.response.data.msg, 'error');
        }
      },

      /**
       * Сохранение формы добавления фотографии
       *
       * @returns {Promise<void>}
       */
      async save() {
        const formData = new FormData();

        for (const prop in this.photo) {
            formData.append(prop, this.photo[prop]);
        }

        try {
          const response = await axios.post(
              `${this.$attrs.apiRoute}/photos`,
              formData,
              {
                headers: {
                  'Content-Type': 'multipart/form-data'
                }
              });

          this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
          this.getPhotos();

          this.resetForm();
        } catch (e) {
          console.log(e.response.data.error);
          this.errors = e.response.data.error;
        }
      },

      /**
       * Закрытие формы создания фотографии
       */
      cancel() {
        this.resetForm();
      },

      /**
       * Очистка формы создания фотографии
       *
       * @returns {Promise<void>}
       */
      async resetForm() {
        this.modal = false;
        this.$refs.form.reset();
        this.photo.image = null;
        this.resetPreview = true;
      },

      /**
       * Обработчик события скролла
       */
      onScroll: function() {
        this.paginationScroll(this, document);
      },

      async initData() {
        await this.getPhotos();
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

            this.getPhotos();
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
    }
  }
</script>

<style scoped>
    .flex-card-full-size {
        display: flex;
        flex-direction: column;
    }
</style>
