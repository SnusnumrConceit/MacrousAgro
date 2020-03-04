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
                        <v-img :src="product.url" alt="" v-if="product.url.length"></v-img>

                        <vue-dropzone ref="product_dropzone"
                                      id="dropzone"
                                      v-else
                                      :options="dropzone_options"
                                      @vdropzone-success="onDropzoneSuccess"
                                      @vdropzone-error="onDropzoneError"
                                      @vdropzone-removed-file="onDropzoneRemoved"
                                      @vdropzone-sending="onDropzoneSending"></vue-dropzone>
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
  import vue2Dropzone from 'vue2-dropzone';
  import 'vue2-dropzone/dist/vue2Dropzone.min.css';
  import dropzone_options from "../../../mixins/dropzone_options";

  export default {
    name: "form",

    mixins: [dropzone_options],

    components: {
      vueDropzone: vue2Dropzone
    },

    data() {
      return {
        product: {
          title: '',
          url: '',
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
        this.modal = false;

        const response = await axios.put(`${this.$attrs.apiRoute}/products/${this.ID}`, this.product);

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

      initializeDropzone() {
        this.dropzone_options.url = `${this.$attrs.apiRoute}/products/upload`;
        this.dropzone_options.dictDefaultMessage = this.$t('dropzone.dictDefaultMessage.images');
        this.dropzone_options.acceptedFiles = '.jpeg,.jpg,.png';
      },

      onDropzoneSuccess(file, response) {
        this.product.url = response.tmp_path;
        this.$refs.product_dropzone.disable();
      },

      onDropzoneError(file, response, xhr) {
        console.log(response.data);
      },

      onDropzoneSending(file, xhr, formData) {
        formData.append('category', 'product');
      },

      async onDropzoneRemoved(file, error, xhr) {
        console.log(file, error, xhr);
        if (error) {
          console.error(error);
          return;
        }

        const response = await axios.post('/admin/products/remove_tmp_product', {path: this.product.url});

        this.$refs.product_dropzone.enable();

        switch (response.status) {
          case 200:
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            break;

          case 500:
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            break;
        }
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

      this.initializeDropzone(
          `${this.$attrs.apiRoute}/upload`,
          this.$t('dropzone.dictDefaultMessage.images'),
          '.jpg,.jpeg,.png'
      );
    }

  }
</script>

<style scoped>

</style>