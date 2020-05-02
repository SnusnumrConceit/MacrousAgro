<template>
    <div>
        <v-card v-show="! loading">
            <v-form v-model="form.valid">
                <v-card-title>
                    {{ photo.title }}
                </v-card-title>
                <v-card-text>
                    <errors></errors>

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
                                Изменить
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="error" outlined @click="remove">
                        {{ $t('photos.btn.delete')}}
                    </v-btn>
                    <v-btn color="default" outlined @click="goBack">
                        {{ $t('photos.btn.back')}}
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>

        <v-skeleton-loader type="card" v-show="loading"></v-skeleton-loader>
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
      ...mapActions('errors', {
        'setErrors': 'setErrors'
      }),

      /**
       * Загрузка фотографии
       *
       * @returns {Promise<boolean>}
       */
      async loadData() {
        try {
          const response = await axios.get(`${this.$attrs.apiRoute}/photos/${this.id}/edit`);
          this.photo = response.data.photo;
        } catch (e) {
          this.$swal(this.$t('swal.title.error', e.response.data.msg, 'error'));
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
          this.loadData();
          this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
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
          this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
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

        await this.loadData();

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
