<template>
    <div>
        <v-card v-show="! loading">
            <v-form v-model="form.valid">
                <v-card-text>
                    <errors></errors>
                    <v-text-field v-model="category.name"
                                  :label="$t('categories.form.labels.title')"
                                  required
                                  clearable
                                  counter
                                  :rules="form.name.rules"
                                  maxlength="25"></v-text-field>
                    </v-card-text>
                <v-card-actions>
                    <v-btn color="success" outlined @click="save" :disabled="! form.valid">
                        {{ $t('buttons.edit')}}
                    </v-btn>
                    <v-btn @click="goBack" color="default" outlined>
                        {{ $t('buttons.back')}}
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>

        <v-skeleton-loader type="table-row-divider@2" v-show="loading" />
    </div>
</template>

<script>
  import { mapActions } from 'vuex';

  export default {
    name: "form",

    data() {
      return {
        category: {
          name: ''
        },

        form: {
          valid: false,

          name: {
            rules: [
              v => (v !== undefined && v.length <= 25) || this.$t('categories.rules.title.max_length', {max_length: 25}),
              v => v !== '' || this.$t('categories.rules.title.required')
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
      ...mapActions('errors', [
        'setErrors'
      ]),

      /**
       * Показ / обнуление уведомлений
       */
      ...mapActions('notifications', [
        'showNotification'
      ]),

      /**
       * Загрузить категорию для редактирования
       *
       * @returns {Promise<void>}
       */
      async getCategory() {
        try {
          const response = await axios.get(`${this.$attrs.apiRoute}/categories/${this.id}/edit`);
          this.category = response.data.category;
        } catch (e) {
          this.showNotification({type: 'error', message: e.response.data.message});
        }
      },

      /**
       * Сохранить изменения категории
       *
       * @returns {Promise<void>}
       */
      async save() {
        try {
          const response = await axios.put(`${this.$attrs.apiRoute}/categories/${this.id}`, { ...this.category});

          this.showNotification({type: 'success', message: response.data.message });
          this.goBack();
        } catch (e) {
          this.setErrors(e.response.data.errors);
        }
      },

      /**
       * Назад
       */
      goBack() {
        this.$router.go(-1);
      },

      /**
       * Инициализация данных для компонента
       *
       * @returns {Promise<void>}
       */
      async initData() {
        this.loading = true;

        await this.getCategory();

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

</style>
