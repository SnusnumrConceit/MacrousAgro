<template>
    <div>
        <div>
            <v-card>
                <v-toolbar>
                    <v-toolbar-title>
                        {{ $t('products.table.header')}}
                    </v-toolbar-title>

                    <v-divider class="mx-4" vertical inset></v-divider>
                    <v-spacer></v-spacer>

                    <v-text-field v-model="search.keyword"
                                  @keyup.enter="onSearch"
                                  append-icon="search"
                                  label="Поиск"
                                  single-line>
                    </v-text-field>

                    <v-spacer></v-spacer>

                    <v-select :items="categories"
                              v-model="search.category"
                              label="Категория"
                              single-line
                              item-text="name"
                              item-value="id">
                    </v-select>

                    <v-spacer></v-spacer>


                    <v-text-field
                            @click="calendar = true"
                            v-model="search.created_at"
                            label="Дата создания"
                            prepend-icon="event"
                            dense
                            readonly
                            clearable
                            hint="Формат даты: ГГГГ-ММ-ДД"
                            persistent-hint
                            single-line

                    ></v-text-field>

                    <v-date-picker :locale="$i18n.locale"
                                   width="550"
                                   :style="{position: 'absolute', right: '10%', top: '100%', 'z-index': 3}"
                                   no-title
                                   scrollable
                                   first-day-of-week="1"
                                   color="primary"
                                   v-if="calendar"
                                   v-model="search.created_at">
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" @click="calendar = false" text>
                            {{ $t('users.btn.cancel') }}
                        </v-btn>
                        <v-btn color="primary" outlined @click="calendar = false">OK</v-btn>
                    </v-date-picker>

                    <v-spacer></v-spacer>

                    <v-dialog v-model="modal" max-width="500px">
                        <template v-slot:activator="{on}">
                            <v-btn color="success" outlined v-on="on">
                                <i class="pe-7s-plus"></i>
                                {{ $t('products.btn.add')}}
                            </v-btn>
                        </template>

                        <v-card>
                            <v-form ref="form" v-model="form.valid">
                            <v-card-title>
                                {{ $t('products.form.header')}}
                            </v-card-title>
                            <v-card-text>
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

                                <vue-dropzone ref="product_dropzone"
                                              id="dropzone"
                                              :options="dropzone_options"
                                              @vdropzone-sending="onDropzoneSending"
                                              @vdropzone-success="onDropzoneSuccess"
                                              @vdropzone-error="onDropzoneError"
                                              @vdropzone-removed-file="onDropzoneRemoved"></vue-dropzone>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>

                                <v-btn color="success" :disabled="! form.valid || ! product.url.length" @click="save()">
                                    {{ $t('products.btn.save')}}
                                </v-btn>
                                <v-btn color="blue darken-1" text @click="cancel()">
                                    {{ $t('products.btn.cancel')}}
                                </v-btn>
                            </v-card-actions>
                            </v-form>
                        </v-card>
                    </v-dialog>
                </v-toolbar>

                <v-card-text>
                    <v-simple-table v-show="products.length && ! loading" :fixed-header="! calendar" :height="750">
                        <thead>
                        <th v-for="header in table.headers" :key="header">
                            {{ header }}
                        </th>
                        </thead>
                        <tbody>
                        <tr v-for="product in products" :key="product.id">
                            <td class="text-left">
                                <v-row>
                                    <v-col cols="2">
                                        <v-img :src="product.url" :aspect-ratio="1.5" contain position="left" v-if="product.url.length"></v-img>
                                        <v-icon v-else>
                                            mdi-image
                                        </v-icon>
                                    </v-col>
                                    <v-col cols="10" class="align-self-sm-center">
                                        <span>
                                            {{ product.title }}
                                        </span>
                                    </v-col>
                                </v-row>
                            </td>
                            <td class="text-left">
                                {{ product.price }} руб.
                            </td>
                            <td class="text-left">
                                {{ product.creation_date }}
                            </td>
                            <td class="text-left">
                                {{ product.updating_date }}
                            </td>
                            <td class="text-right">
                                <v-tooltip top color="primary">
                                    <template v-slot:activator="{ on }">
                                        <v-icon v-on="on"
                                                small
                                                @click="$router.push(`/admin/products/${product.id}`)"
                                        >
                                            mdi-pencil
                                        </v-icon>
                                    </template>
                                    <span>
                                    Править
                                </span>
                                </v-tooltip>

                                <v-tooltip top color="error">
                                    <template v-slot:activator="{ on }">
                                        <v-icon color="red"
                                                v-on="on"
                                                small
                                                @click="remove(product.id)"
                                        >
                                            mdi-delete
                                        </v-icon>
                                    </template>
                                    <span>
                                    Удалить
                                </span>
                                </v-tooltip>
                            </td>
                        </tr>
                        </tbody>
                    </v-simple-table>
                    <span v-show="! loading && products.length" class="d-flex flex-row-reverse">
                        Всего: {{ pagination.total }}
                    </span>

                    <v-skeleton-loader type="table-row-divider@6" v-show="loading"></v-skeleton-loader>
                    <!--<v-row>-->
                        <!--<v-col lg="4" md="6" sm="12" xs="12" v-for="(product, index) in products" :key="product.id">-->
                            <!--<v-card>-->
                                <!--<v-card-title>-->
                                    <!--{{ product.title }}-->
                                <!--</v-card-title>-->
                                <!--<v-card-text>-->
                                    <!--<v-img :src="product.url" alt=""></v-img>-->
                                <!--</v-card-text>-->
                                <!--<v-card-actions>-->
                                    <!--<v-btn color="error" outlined @click="remove(product.id)">-->
                                        <!--{{ $t('products.btn.delete')}}-->
                                    <!--</v-btn>-->
                                    <!--<v-btn color="warning" outlined-->
                                           <!--@click="$router.push('/admin/products/' + product.id)">-->
                                        <!--{{ $t('products.btn.edit')}}-->
                                    <!--</v-btn>-->
                                <!--</v-card-actions>-->
                            <!--</v-card>-->
                        <!--</v-col>-->
                        <!--<v-pagination :length="pagination.last_page"-->
                        <!--circle-->
                        <!--v-model="pagination.page"-->
                        <!--:total-visible="7"></v-pagination>-->
                    <!--</v-row>-->
                </v-card-text>
                <v-alert type="info" outlined v-show="! loading && ! products.length">
                    <div class="title">
                        <span v-show="! searching">
                            Товары отсутствуют в системе
                        </span>
                        <span v-show="searching">
                            По Вашему запросу ничего не найдено
                        </span>
                    </div>
                </v-alert>
            </v-card>
        </div>
    </div>
</template>

<script>
  import debounce from '../../../debounce';
  import vue2Dropzone from 'vue2-dropzone';
  import 'vue2-dropzone/dist/vue2Dropzone.min.css';
  import dropzone_options from "../../../mixins/dropzone_options";
  import infiniteScrollMixin from "../../../mixins/infinite_scroll";

  export default {
    name: "index",

    mixins: [dropzone_options, infiniteScrollMixin],

    components: {
      vueDropzone: vue2Dropzone
    },

    data() {
      return {
        products: [],

        product: {
          title: '',
          description: '',
          url: '',
          price: '',
          category_id: '',
          media_id: ''
        },

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

        table: {
          headers: [
            'Наименование',
            'Стоимость',
            'Создан',
            'Последнее изменение',
            ''
          ]
        },

        search: {
          keyword: '',
          created_at: null,
          category: ''
        },

        modal: false,
        calendar: false,

        pagination: {
          page: 1,
          last_page: 1,
          total: ''
        },

        categories: [],

        searching: false,
        loading: false,

        isDestroying: false

      }
    },

    methods: {
      async loadData() {
        this.loading = true;
        const response = await axios.get(`${this.$attrs.apiRoute}/products`, {
          params: {
            page: this.pagination.page
          }
        });

        this.products = (this.pagination.page === 1) ? response.data.products.data : this.products.concat(response.data.products.data);
        this.pagination.last_page = response.data.products.last_page;
        this.pagination.total = response.data.products.total;
        this.loading = false;
      },

      async loadCategories() {
        const response = await axios.get(`${this.$attrs.apiRoute}/categories`);

        this.categories = response.data.categories;
      },

      onSearch() {
        this.searchData(this);
      },

      searchData: debounce(vm => {
        axios.get('/api/products/search', {
          params: {
            paeg: vm.page,
            keyword: vm.search.keyword,
            created_at: vm.search.created_at,
            category: vm.search.category
          }
        }).then(response => {
          vm.products = (vm.pagination.page === 1) ? response.data.products.data : vm.products.concat(response.data.products.data);
          vm.pagination.last_page = response.data.products.last_page;
          vm.pagination.total = response.data.products.total;
        }).catch(error => console.error(error));
      }, 300),

      async remove(id) {
        const response = await axios.delete(`${this.$attrs.apiRoute}/products/${id}`);

        switch (response.data.status) {
          case 'error':
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            return false;

          case 'success':
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            this.loadData();
        }
      },

      async save() {
        this.modal = false;

        const response = await axios.post(`${this.$attrs.apiRoute}/products`, this.product);

        switch (response.status) {
          case 200:
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            this.loadData();
            break;

          case 500:
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            break;
        }


        this.resetForm();
      },

      cancel() {
        this.modal = false;
        this.resetForm();
      },

      async resetForm() {
        this.$refs.form.reset();
        this.isDestroying = true;
        await this.$refs.product_dropzone.removeAllFiles();
        this.$refs.product_dropzone.enable();
        this.isDestroying = false;
      },

      initializeDropzone() {
        // this.$refs.product_dropzone.setOption('url', '/admin/media');
        // this.$refs.product_dropzone.setOption('dictDefaultMessage', this.$t('dropzone.dictDefaultMessage.images'));
        // this.$refs.product_dropzone.setOption('acceptedFiles', '.jpeg,.jpg,.png');
        this.dropzone_options['url'] = `${this.$attrs.apiRoute}/media`;
        this.dropzone_options['dictDefaultMessage'] = this.$t('dropzone.dictDefaultMessage.images');
        this.dropzone_options['acceptedFiles'] = '.jpeg,.jpg,.png';
      },

      onDropzoneSuccess(file, response) {
        this.product.url = response.url;
        this.product.media_id = response.media_id;

        this.$refs.product_dropzone.disable();
      },

      onDropzoneError(file, response, xhr) {
        console.log(response.data);
      },

      onDropzoneSending(file, xhr, formData) {
        formData.append('category', 'product');
      },


      async onDropzoneRemoved(file, error, xhr) {
        if  (this.isDestroying) {
          return ;
        }

        console.log(file, error, xhr);
        if (error) {
          console.error(error);
          return;
        }

        const response = await axios.post(`/admin/media/${this.product.media_id}/destroy`, {
          path: this.product.url,
          category: 'product'
        });

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

      // parseDate (date) {
      //   if (!date) return null;
      //
      //   console.log(date);
      //   const [year, month, day] = date.split('-');
      //   return `${day.padStart(2, '0')}.${month.padStart(2, '0')}.${year}`
      // },

      async initData() {
        this.loading = true;

        await this.loadCategories();

        await this.loadData();

        this.loading = false;
      },

      onScroll: function() {
        this.paginationScroll(this, $('.v-data-table__wrapper')[0]);
      },
    },

    watch: {
      'search': {
        handler: function(after, before) {
          if (after.category || after.created_at || after.keyword.length > 3) {
            this.pagination.page = 1;
            this.searching = true;

            this.searchData(this);
          }
        },

        deep: true
      },

      'search.keyword': function (after, before) {
        if (after.length > 3) {
          this.onSearch();
        }
      }
    },

    created() {
      this.initData();
    },

    mounted() {
      this.initializeDropzone(
          `${this.$attrs.apiRoute}/products/upload`,
          this.$t('dropzone.dictDefaultMessage.images'),
          '.jpg,.jpeg,.png'
      );

      $('.v-data-table__wrapper')[0].addEventListener('scroll', this.onScroll);
    },

    beforeDestroy() {
      this.isDestroying = true;
    }
  }
</script>

<style scoped>

</style>