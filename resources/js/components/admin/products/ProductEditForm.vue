<template>
    <div>
        <v-card v-show="! loading">
            <v-form ref="form" v-model="form.valid">
            <v-card-title>
                {{ product.title }}
            </v-card-title>
            <v-card-text>
                <errors></errors>
                <v-row>
                    <v-col>
                        <preview-upload @uploaded="onUploadImage" ref="previewUpload" v-if="! loading" :src="product.src" />
                    </v-col>
                    <v-col>
                        <v-text-field v-model="product.title"
                                      :label="$t('products.form.labels.title')"
                                      clearable
                                      counter
                                      maxlength="100"
                                      :rules="form.title.rules">

                        </v-text-field>

                        <v-select v-model="product.category_id"
                                  :items="categories"
                                  :label="$t('products.form.labels.category')"
                                  item-text="name"
                                  item-value="id"
                                  clearable
                                  :rules="form.category.rules"
                        ></v-select>

                        <v-textarea v-model="product.description"
                                    :label="$t('products.form.labels.description')"
                                    required
                                    counter
                                    maxlength="2000"
                                    :rules="form.description.rules">

                        </v-textarea>

                        <v-text-field v-model="product.price"
                                      :label="$t('products.form.labels.price')"
                                      clearable
                                      counter
                                      maxlength="10"
                                      :rules="form.price.rules">
                        </v-text-field>

                        <v-btn color="success"
                               :disabled="! form.valid"
                               outlined
                               @click="save">
                            {{ $t('buttons.edit') }}
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
  import previewUpload from '../../../custom_components/previewUploader';
  import { mapActions } from 'vuex';

  export default {
    name: "form",

    components: {
      previewUpload
    },

    data() {
      return {
        product: {
          title: '',
          description: '',
          price: '',
          category_id: ''
        },

        categories: [],

        form: {
          valid: false,

          title: {
            rules: [
              v => (v !== ''&& v !== undefined && v !== null) || this.$t('products.form.rules.title.required'),
              v => (v !== undefined && v !== null && v.length <= 100) || this.$t('products.form.rules.title.max_length', {length: 100})
            ],
          },

          description: {
            rules: [
              v => v !== '' && v.length || this.$t('products.form.rules.description.required'),
              v => (v !== undefined && v !== null && v.length <= 2000) || this.$t('products.form.rules.title.length', {length: 2000})
            ]
          },

          price: {
            rules: [
              v => v !== '' || this.$t('products.form.rules.price.required'),
              v => !isNaN(v) || this.$t('products.form.rules.price.not_numeric'),
            ]
          },

          category: {
            rules: [
              v => v !== '' || this.$t('products.form.rules.category.required')
            ]
          }
        },

        selectedCategory: {},

        loading: false
      }
    },

    computed: {
      ID() {
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
       * Обработчик загрузки изображения товара
       *
       * @param image
       */
      onUploadImage(image) {
        this.product.image = image;
      },

      /**
       * Загрузка товара для редактирования
       *
       * @returns {Promise<void>}
       */
      async getProduct() {
        try {
          const response = await axios.get(`${this.$attrs.apiRoute}/products/${this.ID}/edit`);
          this.product = response.data.product;
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message});
        }
      },

      /**
       * Загрузка списка категорий
       *
       * @returns {Promise<void>}
       */
      async loadCategories() {
        try {
          const response = await axios.get(`${this.$attrs.apiRoute}/categories`);

          this.categories = response.data.categories;
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message});
        }
      },

      /**
       * Удаление товара
       *
       * @returns {Promise<void>}
       */
      async remove() {
        try {
          const response = await axios.delete(`${this.$attrs.apiRoute}/products/${this.ID}`);

          this.showNotification({ type: 'success', message: response.data.message});
          this.goBack();
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message});
        }
      },

      /**
       * Сохранение изменений для товара
       *
       * @returns {Promise<void>}
       */
      async save() {
        const formData = new FormData();

        for (const prop in this.product) {
          formData.append(prop, this.product[prop]);
        }

        formData.append('_method', 'PATCH');

        try {
          const response = await axios.post(
              `${this.$attrs.apiRoute}/products/${this.ID}`,
              formData,
              {
                headers: {
                  'Content-Type': 'multipart/form-data'
                }
              }
          );

          this.showNotification({ type: 'success', message: response.data.message});
          this.goBack();
        } catch (e) {
          this.setErrors(e.response.data.error);
        }
      },

      /**
       * Назад
       */
      goBack() {
        this.$router.go(-1);
      },

      /**
       * Инициализация компонента данными
       *
       * @returns {Promise<void>}
       */
      async initData() {
        this.loading = true;

        await this.loadCategories();

        await this.getProduct();

        this.loading = false;
      }
    },

    created() {
      this.initData();
    },

    beforeDestroy() {
      this.hideNotification();
    }

  }
</script>

<style scoped>

</style>