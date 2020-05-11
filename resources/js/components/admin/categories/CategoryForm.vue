<template>
    <v-card>
        <v-form v-model="form.valid" ref="form" v-show="! loading">
            <v-card-title class="headline">
                {{ $t('categories.form.header')}}
            </v-card-title>

            <v-card-text>
                <errors></errors>
                <v-text-field :label="$t('categories.form.labels.title')"
                              required
                              v-model="category.name"
                              clearable
                              counter
                              :rules="form.name.rules"
                              maxlength="25">
                </v-text-field>
            </v-card-text>

            <v-card-actions>
                <v-spacer v-if="! id"></v-spacer>

                <v-btn color="success"
                       outlined
                       :disabled="! form.valid"
                       @click="id ? update() : create()">
                    {{ $t(saveBtn)}}
                </v-btn>
                <v-btn color="blue darken-1"
                       text
                       @click="id ? $router.back() : close()">
                    {{ $t(cancelBtn) }}
                </v-btn>
            </v-card-actions>
        </v-form>

        <v-skeleton-loader type="table-row-divider@2" v-show="loading" />
    </v-card>
</template>

<script>
  import {mapActions} from 'vuex';

  export default {
    name: "CategoryForm",

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
      },

      saveBtn() {
        return this.id ? 'buttons.edit' : 'buttons.save';
      },

      cancelBtn() {
        return this.id ? 'buttons.back' : 'buttons.cancel';
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
       * Загрузить категорию для редактирования
       *
       * @returns {Promise<void>}
       */
      async getCategory() {
        try {
          const response = await axios.get(`/categories/${this.id}/edit`);
          this.category = response.data.category;
        } catch (e) {
          this.showNotification({type: 'error', message: e.response.data.message});
        }
      },

      /**
       * Создание категории
       *
       * @returns {Promise<void>}
       */
      async create() {
        try {
          const response = await axios.post(`/categories`, {
            ...this.category
          });
          this.showNotification({type: 'success', message: response.data.message });

          this.$root.$emit('category-created', this.category);
          this.resetForm();
          this.close();
        } catch (e) {
          this.setErrors(e.response.data.errors || e.response.data.message);
        }
      },

      /**
       * Обновление категории
       *
       * @returns {Promise<void>}
       */
      async update() {
        try {
          const response = await axios.put(`/categories/${this.id}`, { ...this.category});

          this.showNotification({type: 'success', message: response.data.message });
          this.$router.back();
        } catch (e) {
          this.setErrors(e.response.data.errors);
        }
      },

      /**
       * Очистка формы добавления категории
       */
      resetForm() {
        this.modal = false;
        this.$refs.form.reset();
        this.resetErrors();
      },

      /**
       * Закрытие формы
       */
      close() {
        this.$emit('hide-modal');
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