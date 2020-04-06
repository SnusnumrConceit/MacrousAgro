<template>
    <div>
        <v-card v-show="! loading">
            <v-form ref="form" v-model="form.valid">
            <v-card-title>
                {{ product.title }}
            </v-card-title>
            <v-card-text>
                <v-row>
                    <v-col>
                        <preview-upload @uploaded="onUploadImage" ref="previewUpload" v-if="! loading" :src="product.src"></preview-upload>
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
                            Изменить
                        </v-btn>
                    </v-col>
                </v-row>
            </v-card-text>
            <v-card-actions>
                <v-btn color="error" outlined @click="remove">
                    {{ $t('products.btn.delete')}}
                </v-btn>
                <v-btn color="default" outlined @click="goBack">
                    {{ $t('products.btn.back')}}
                </v-btn>
            </v-card-actions>
            </v-form>
        </v-card>

        <v-skeleton-loader type="card" v-show="loading"></v-skeleton-loader>
    </div>
</template>

<script>
  import previewUpload from '../../../custom_components/previewUploader';

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
              v => v !== '' || this.$t('products.form.rules.title.required'),
              v => (v !== undefined && v !== null && v.length <= 255) || this.$t('products.form.rules.title.length', {length: 255})
            ],
          },

          description: {
            rules: [
              v => v !== '' || this.$t('products.form.rules.description.required'),
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
      onUploadImage(image) {
        this.product.image = image;
      },

      async loadData() {
        const response = await axios.get(`${this.$attrs.apiRoute}/products/${this.ID}/edit`);

        if (response.status === 200) {
          this.product = response.data.product;
        } else {
          console.error(response);
        }
      },

      async loadCategories() {
        const response = await axios.get(`${this.$attrs.apiRoute}/categories`);

        if (response.status === 200) {
          this.categories = response.data.categories;
        } else {
          console.error(response);
        }
      },

      async remove() {
        const response = await axios.delete(`${this.$attrs.apiRoute}/products/${this.ID}`);

        switch (response.data.status) {
          case 'error':
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            break;

          case 'success':
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            this.$router.go(-1);
            break;
        }
      },

      async save() {
        const formData = new FormData();

        for (const prop in this.product) {
          formData.append(prop, this.product[prop]);
        }

        formData.append('_method', 'PATCH');

        const response = await axios.post(
            `${this.$attrs.apiRoute}/products/${this.ID}`,
            formData,
            {
              headers: {
                'Content-Type': 'multipart/form-data'
              }
            }
        );

        switch (response.status) {
          case 200:
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            this.goBack();
            break;

          case 500:
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            break;
        }
      },

      goBack() {
        this.$router.go(-1);
      },

      async initData() {
        this.loading = true;

        await this.loadCategories();

        await this.loadData();

        this.loading = false;
      }
    },

    created() {
      this.initData();
    }

  }
</script>

<style scoped>

</style>