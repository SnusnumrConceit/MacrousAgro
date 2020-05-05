<template>
    <div>
        <v-card v-show="! loading">
            <v-form ref="form" v-model="form.valid">
                <v-card-title>
                    {{ video.title }}
                </v-card-title>
                <v-card-text>
                    <errors />

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
                            <v-btn color="success"
                                   :disabled="! form.valid"
                                   outlined
                                   @click="save">
                                {{ $t('buttons.edit')}}
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="error" outlined @click="remove">
                        {{ $t('buttons.delete')}}
                    </v-btn>
                    <v-btn color="default" outlined @click="goBack">
                        {{ $t('buttons.back')}}
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>

        <v-skeleton-loader type="card" v-show="loading" />
    </div>
</template>

<script>
  import {mapActions} from 'vuex';

  export default {
    name: "form",

    data() {
      return {
        video: {
          title: '',
          path: ''
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

        loading: false
      }
    },

    computed: {
      id() {
        return this.$route.params.id;
      }
    },

    methods: {
      /**
       * Показ ошибок в форме
       */
      ...mapActions('errors', {
        'resetErrors': 'resetErrors',
        'setErrors': 'setErrors'
      }),

      /**
       * Показ / обнуление уведомлений
       */
      ...mapActions('notifications', {
        'showNotification': 'showNotification',
        'hideNotification': 'hideNotification'
      }),

      /**
       * Получение видео
       *
       * @returns {Promise<void>}
       */
      async loadData() {
        try {
          const response = await axios.get(`${this.$attrs.apiRoute}/videos/${this.id}/edit`);

          this.video = response.data.video;
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message});
        }
      },

      /**
       * Удаление видео
       *
       * @returns {Promise<void>}
       */
      async remove() {
        try {
          const response = await axios.delete(`${this.$attrs.apiRoute}/videos/${this.id}`);

          this.showNotification({ type: 'success', message: response.data.message});
          this.goBack();
        } catch (e) {
          this.setErrors(e.response.data.error);
        }
      },

      /**
       * Сохранение видео
       *
       * @returns {Promise<void>}
       */
      async save() {
        try {
          const response = await axios.put(`${this.$attrs.apiRoute}/videos/${this.id}`, this.video);

          this.showNotification({ type: 'success', message: response.data.message});
          this.goBack();
        } catch (e) {
          this.setErrors(e.response.data.error);
        }
      },

      /**
       * Назад
       *
       */
      goBack() {
        this.$router.go(-1);
      },

      /**
       * Lazy-загрузка видео
       *
       * @returns {Promise<void>}
       */
      async initData() {
        this.loading = true;

        await this.loadData();

        this.loading = false;
      }
    },

    created() {
      if (this.id !== undefined) {
        this.initData();
      }
    },

    beforeDestroy() {
      this.hideNotification();
    }
  }
</script>

<style scoped>

</style>
