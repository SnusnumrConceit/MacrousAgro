<template>
    <div>
        <v-card v-show="! loading">
            <v-form ref="form" v-model="form.valid">
                <v-card-title>
                    {{ formTitle }}
                </v-card-title>
                <v-card-text v-if="! id">
                    <errors />

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

                    <preview-upload @uploaded="onUploadImage" ref="previewUpload" :reset="resetPreview" />

                </v-card-text>

                <v-card-text v-else>
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
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions>
                    <v-spacer />

                    <v-btn color="success"
                           outlined
                           :disabled="isValid"
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
    name: "ProductForm",

    components: {
      previewUpload
    },

    data() {
      return {
        product: {
          title: '',
          description: '',
          price: '',
          category_id: '',
          image: null
        },

        categories: [],

        form: {
          valid: false,
          title: {
            rules: [
              v => v !== '' || this.$t('products.form.rules.title.required'),
              v => (v !== undefined && v !== null && v.length <= 255) || this.$t('products.form.rules.title.max_length', {length: 255})
            ],
          },

          description: {
            rules: [
              v => v !== '' || this.$t('products.form.rules.description.required'),
              v => (v !== undefined && v !== null && v.length <= 2000) || this.$t('products.form.rules.description.max_length', {length: 2000})
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
              v => v !== '' && v !== undefined && v !== null || this.$t('products.form.rules.category.required')
            ]
          }
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
        return this.id ? this.product.title : this.$t('products.form.header');
      },

      isValid() {
        return ! this.form.valid || (this.id ? false : ! this.product.image);
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
       * Обработчик события загрузки картинки товара
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
          const response = await axios.get(`/products/${this.id}/edit`);
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
      async getCategories() {
        try {
          const response = await axios.get(`/categories`);

          this.categories = response.data.categories;
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message});
        }
      },

      /**
       * Добавление товара
       *
       * @returns {Promise<void>}
       */
      async create() {
        let formData = new FormData();

        for (const prop in this.product) {
          formData.append(prop, this.product[prop]);
        }

        try {
          const response = await axios.post(
              `/products`,
              formData,
              {
                headers: {
                  'Content-Type': 'multipart/form-data'
                }
              }
          );

          this.showNotification({ type: 'success', message: response.data.message});

          this.$root.$emit('product-created');
          this.hide();
        } catch (e) {
          this.setErrors(e.response.data.errors);
        }
      },

      /**
       * Сохранение изменений для товара
       *
       * @returns {Promise<void>}
       */
      async update() {
        const formData = new FormData();

        for (const prop in this.product) {
          formData.append(prop, this.product[prop]);
        }

        formData.append('_method', 'PATCH');

        try {
          const response = await axios.post(
              `/products/${this.id}`,
              formData,
              {
                headers: {
                  'Content-Type': 'multipart/form-data'
                }
              }
          );

          this.showNotification({ type: 'success', message: response.data.message});
          this.$router.back();
          this.resetForm();
        } catch (e) {
          this.setErrors(e.response.data.errors);
        }
      },

      /**
       * Закрыть модалку
       */
      hide() {
        this.resetForm();

        this.$emit('hide-modal');
      },

      /**
       * Обнуление формы создания товара
       *
       * @returns {Promise<void>}
       */
      async resetForm() {
        this.modal = false;
        this.product.image = null;
        this.resetPreview = true;
        this.$refs.form.reset();
      },

      async initData() {
        this.loading = true;

        await this.getCategories();

        if (this.id) {
          await this.getProduct();
        }

        this.loading = false;
      }
    },

    created() {
      this.initData();
    }
  }
</script>