<template>
    <div>
        <v-card v-show="! loading">
            <v-form v-model="form.valid">
                <v-card-title>
                    {{ photo.title }}
                </v-card-title>
                <v-card-text>
                    <errors />

                    <v-row>
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
                            <v-btn color="success" :disabled="! form.valid" outlined @click="save">
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
  import { mapActions } from 'vuex';

  export default {
    name: "form",

    data() {
      return {
        photo: {
          title: '',
          path: ''
        },

        form: {
          valid: false,
          title: {
            rules: [
              v => v !== '' || 'Поле обязательное к заполнению',
              v => (v !== undefined && v !== null && v.length <= 100) || 'Длина не может превышать 25 символов'
            ]
          }
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
       * Загрузка фотографии
       *
       * @returns {Promise<boolean>}
       */
      async getPhoto() {
        try {
          const response = await axios.get(`${this.$attrs.apiRoute}/photos/${this.id}/edit`);
          this.photo = response.data.photo;
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message});
        }
      },

      /**
       * Удаление фотографии
       *
       * @returns {Promise<void>}
       */
      async remove() {
        try {
          const response = await axios.delete(`${this.$attrs.apiRoute}/photos/${this.id}`);
          this.getPhoto();
          this.showNotification({ type: 'success', message: response.data.message});
          this.goBack();
        } catch (e) {
          this.setErrors( e.response.data.error);
        }
      },

      /**
       * Сохранение изменений фотографии
       *
       * @returns {Promise<void>}
       */
      async save() {
        try {
          const response = await axios.put(`${this.$attrs.apiRoute}/photos/${this.id}`, this.photo);
          this.showNotification({ type: 'success', message: response.data.message});
          this.goBack();
        } catch (e) {
          this.setErrors(e.response.data.error);
        }
      },

      /**
       * Переход "Назад"
       */
      goBack() {
        this.$router.go(-1);
      },

      /**
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
    },

    beforeDestroy() {
      this.hideNotification();
    }
  }
</script>

<style scoped>
    .img-container {
        max-height: 600px;
        max-width: 850px;
    }
</style>
