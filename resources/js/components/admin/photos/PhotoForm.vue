<template>
    <div>
        <v-card v-show="! loading">
            <v-form ref="form" v-model="form.valid">
                <v-card-title>
                    {{ formTitle }}
                </v-card-title>
                <v-card-text>
                    <errors />

                    <v-row v-if="id">
                        <v-col>
                            <v-img :src="photo.path" alt=""></v-img>
                        </v-col>
                        <v-col>
                            <v-text-field v-model="photo.title"
                                          :label="$t('photos.form.labels.title')"
                                          clearable
                                          counter
                                          :rules="form.title.rules"
                                          maxlength="100">
                            </v-text-field>
                            <!--<v-btn color="success" -->
                                   <!--:disabled="! form.valid" -->
                                   <!--outlined @click="save">-->
                                <!--{{ $t('buttons.edit')}}-->
                            <!--</v-btn>-->
                        </v-col>
                    </v-row>

                    <div v-else>
                        <v-text-field v-model="photo.title"
                                      :label="$t('photos.form.labels.title')"
                                      clearable
                                      :rules="form.title.rules"
                                      counter
                                      maxlength="100"
                        >

                        </v-text-field>

                        <preview-upload @uploaded="onUploadImage" :reset="resetPreview" />
                    </div>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn color="success"
                           :disabled="isValid"
                           outlined
                           @click="id ? update() : create()">
                        {{ $t(btnSave)}}
                    </v-btn>
                    <v-btn color="blue darken-1"
                           text
                           @click="id ? $router.back() : hide()">
                        {{ $t(btnCancel)}}
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>

        <v-skeleton-loader type="card" v-show="loading" />
    </div>
</template>

<script>
  import { mapActions } from 'vuex';

  import previewUpload from '../../../custom_components/previewUploader';

  export default {
    name: "PhotoForm",

    components: {
      previewUpload
    },

    data() {
      return {
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

        resetPreview: false,
        loading: false
      }
    },

    computed: {
      id() {
        return this.$route.params.id;
      },

      btnSave() {
        return this.id ? 'buttons.edit' : 'buttons.save';
      },

      btnCancel() {
        return this.id ? 'buttons.back' : 'buttons.cancel';
      },

      formTitle() {
        return this.id ? this.photo.title : this.$t('photos.form.header');
      },

      isValid() {
        return ! this.form.valid || (this.id ? false : ! this.photo.image);
      }
    },

    methods: {
      /**
       * Показ ошибок в форме
       */
      ...mapActions('errors', [
        'resetErrors',
        'setErrors'
      ]),

      /**
       * Показ / обнуление уведомлений
       */
      ...mapActions('notifications', [
        'showNotification'
      ]),

      /**
       * Загрузка фотографии
       *
       * @returns {Promise<boolean>}
       */
      async getPhoto() {
        try {
          const response = await axios.get(`/photos/${this.id}/edit`);
          this.photo = response.data.photo;
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message});
        }
      },


      /**
       * Обработчик события в форме добавления - отслеживает загрузку фотографии
       *
       * @param image
       */
      onUploadImage(image) {
        this.photo.image = image;
      },

      /**
       * Создание фотографии
       *
       * Create photo
       *
       * @returns {Promise<void>}
       */
      async create() {
        const formData = new FormData();

        for (const prop in this.photo) {
          formData.append(prop, this.photo[prop]);
        }

        try {
          const response = await axios.post(
              `/photos`,
              formData,
              {
                headers: {
                  'Content-Type': 'multipart/form-data'
                }
              });

          this.showNotification({ type: 'success', message: response.data.message});

          this.$root.$emit('photo-created');
          this.hide();
        } catch (e) {
          this.setErrors(e.response.data.errors);
        }
      },

      /**
       * Обновление фотографии
       *
       * Update photo
       *
       * @returns {Promise<void>}
       */
      async update() {
        try {
          const response = await axios.put(`/photos/${this.id}`, this.photo);

          this.showNotification({ type: 'success', message: response.data.message});
          this.$router.back();
        } catch (e) {
          this.setErrors(e.response.data.errors);
        }
      },

      /**
       * Закрытие формы создания фотографии
       */
      hide() {
        this.resetForm();
        this.$emit('hide-modal');
      },

      /**
       * Очистка формы создания фотографии
       *
       * @returns {Promise<void>}
       */
      async resetForm() {
        this.$refs.form.reset();
        this.photo.image = null;
        this.resetPreview = true;
      },

      /**
       * Инициализация фотографии данными
       *
       * @returns {Promise<void>}
       */
      async initData() {
        this.loading = true;

        await this.getPhoto();

        this.loading = false;
      }
    },

    created() {
      if (this.id) {
        this.initData();
      }
    }
  }
</script>

<style scoped>
    .img-container {
        max-height: 600px;
        max-width: 850px;
    }
</style>