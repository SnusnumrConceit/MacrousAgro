<template>
    <div>
        <v-card v-show="! loading">
            <v-form v-model="form.valid" ref="form">
                <v-card-title>
                    {{ formTitle }}
                </v-card-title>
                <v-card-text>
                    <errors />

                    <div v-if="! id">
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
                                        :reset="resetPreview" />
                    </div>
                    <div v-else>
                        <v-row>
                            <v-col>
                                <video :src="video.src" controls width="520" height="380"></video>
                            </v-col>
                            <v-col>
                                <v-text-field v-model="video.title"
                                              :label="$t('videos.form.labels.title')"
                                              clearable
                                              counter
                                              :rules="form.title.rules"
                                              maxlength="100">
                                </v-text-field>
                            </v-col>
                        </v-row>
                    </div>

                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn color="success"
                           outlined
                           :disabled="isValid"
                           @click="id ? update() : create()">
                        {{ $t(btnSave)}}
                    </v-btn>
                    <v-btn color="blue darken-1" @click="id ? $router.back() : hide()" text>
                        {{ $t(btnCancel)}}
                    </v-btn>
                </v-card-actions>

            </v-form>
        </v-card>

        <v-skeleton-loader type="card" v-show="loading" />
    </div>
</template>

<script>
  import previewUpload from '../../../custom_components/previewUploader';

  import {mapActions} from 'vuex';

  export default {
    name: "VideoForm",

    components: {
      previewUpload
    },

    data() {
      return {
        extensions: ['.mp4', '.mpeg', '.mpg'],

        video: {
          title: '',
          video: null
        },

        form: {
          valid: false,
          title: {
            rules: [
              v => v !== '' || this.$t('videos.form.rules.title.required'),
              v => (v !== undefined && v !== null && v.length <= 100) || this.$t('videos.form.rules.title.length', {length: 255})
            ]
          },
        },

        resetPreview: false,
        loading: false
      }
    },

    computed: {
      id() {
        return this.$route.params.id
      },

      btnSave() {
        return this.id ? 'buttons.edit' : 'buttons.save';
      },

      btnCancel() {
        return this.id ? 'buttons.back' : 'buttons.cancel';
      },

      isValid() {
        return ! this.form.valid || (this.id ? false : ! this.video.video);
      },

      formTitle() {
        return this.id ? this.video.title : this.$t('videos.form.header');
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
       * Обработчик события в форме добавления - отслеживает загрузку видео
       * @param video
       */
      onUpload(video) {
        this.video.video = video;
      },

      /**
       * Создание видео
       *
       * @returns {Promise<void>}
       */
      async create() {
        const formData = new FormData();

        for (const prop in this.video) {
          formData.append(prop, this.video[prop]);
        }

        try {
          const response = await axios.post(
              `/videos`,
              formData,
              {
                headers: {
                  'Content-Type': 'multipart/form-data'
                }
              }
          );

          this.showNotification({ type: 'success', message: response.data.message});

          this.$root.$emit('video-created');

          this.hide();
        } catch (e) {
          this.setErrors(e.response.data.errors);
        }
      },

      /**
       * Обновление видео
       *
       * @returns {Promise<void>}
       */
      async update() {
        try {
          const response = await axios.patch(`/videos/${this.id}`, this.video);

          this.showNotification({ type: 'success', message: response.data.message});
          this.$router.back();
        } catch (e) {
          this.setErrors(e.response.data.errors);
        }
      },

      /**
       * Получение видео
       *
       * @returns {Promise<void>}
       */
      async getVideo() {
        try {
          const response = await axios.get(`/videos/${this.id}/edit`);

          this.video = response.data.video;
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message});
        }
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

      /**
       * Закрытие модального окна
       *
       */
      hide() {
        this.$emit('hide-modal');
        this.resetForm();
      },

      /**
       * Lazy-загрузка видео
       *
       * @returns {Promise<void>}
       */
      async initData() {
        this.loading = true;

        await this.getVideo();

        this.loading = false;
      }
    },

    created() {
      if (this.id !== undefined) {
        this.initData();
      }
    }
  }
</script>