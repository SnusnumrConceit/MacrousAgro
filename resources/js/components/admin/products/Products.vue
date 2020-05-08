<template>
    <div>
        <div>
            <v-card>
                <v-toolbar>
                    <v-toolbar-title>
                        {{ $t('products.products')}}
                    </v-toolbar-title>

                    <v-divider class="mx-4" vertical inset></v-divider>
                    <v-spacer></v-spacer>

                    <v-text-field v-model="search.keyword"
                                  @keyup.enter="onSearch"
                                  append-icon="search"
                                  :label="$t('placeholders.search')"
                                  single-line>
                    </v-text-field>

                    <v-spacer></v-spacer>

                    <v-select :items="categories"
                              v-model="search.category"
                              :label="$t('products.placeholders.category')"
                              single-line
                              item-text="name"
                              item-value="id"
                              clearable>
                    </v-select>

                    <v-spacer></v-spacer>

                    <v-menu ref="search-calendar-menu"
                            v-model="calendar"
                            :close-on-content-click="false"
                            transition="scale-transition"
                            offset-y
                            max-width="290px"
                            min-width="200px">
                        <template v-slot:activator="{ on }">
                            <v-text-field
                                    v-on="on"
                                    v-model="search.display_created_at"
                                    @input="calendar = false"
                                    :label="$t('placeholders.created_at')"
                                    prepend-icon="event"
                                    dense
                                    readonly
                                    clearable
                                    :hint="$t('placeholders.created_at')"
                                    persistent-hint
                                    single-line

                            ></v-text-field>
                        </template>

                        <v-date-picker :locale="$i18n.locale"
                                       width="290"
                                       no-title
                                       scrollable
                                       first-day-of-week="1"
                                       color="primary"
                                       v-if="calendar"
                                       v-model="search.created_at">
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" @click="calendar = false" text>
                                {{ $t('buttons.cancel') }}
                            </v-btn>
                            <v-btn color="primary" outlined @click="calendar = false">OK</v-btn>
                        </v-date-picker>
                    </v-menu>

                    <v-spacer></v-spacer>

                    <v-dialog v-model="modal" max-width="500px" persistent>
                        <template v-slot:activator="{on}">
                            <v-btn color="success"
                                   outlined
                                   v-on="on"
                                   @click="resetPreview">
                                <i class="pe-7s-plus"></i>
                                {{ $t('buttons.add')}}
                            </v-btn>
                        </template>

                        <v-card>
                            <v-form ref="form" v-model="form.valid">
                            <v-card-title>
                                {{ $t('products.form.header')}}
                            </v-card-title>
                            <v-card-text>
                                <errors></errors>

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

                            <v-card-actions>
                                <v-spacer></v-spacer>

                                <v-btn color="success" :disabled="! form.valid" @click="save()">
                                    {{ $t('buttons.save')}}
                                </v-btn>
                                <v-btn color="blue darken-1" text @click="cancel()">
                                    {{ $t('buttons.cancel')}}
                                </v-btn>
                            </v-card-actions>
                            </v-form>
                        </v-card>
                    </v-dialog>
                </v-toolbar>

                <v-card-text>
                    <v-simple-table v-show="! loading && products.length" :fixed-header="! calendar" :height="750">
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
                                        <v-img :src="product.src" :aspect-ratio="1.5" contain position="left" v-if="product.src.length"></v-img>
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
                                {{ product.created_at }}
                            </td>
                            <td class="text-left">
                                {{ product.updated_at }}
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
                                    {{ $t('tooltips.edit') }}
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
                                    {{ $t('tooltips.delete') }}
                                </span>
                                </v-tooltip>
                            </td>
                        </tr>
                        </tbody>
                    </v-simple-table>
                    <span v-show="! loading && products.length" class="d-flex flex-row-reverse">
                        {{ $t('total', {total: pagination.total}) }}
                    </span>

                    <v-skeleton-loader type="table-row-divider@6" v-show="loading" />

                    <v-alert color="info" outlined v-if="! loading && ! products.length">
                        <div class="">
                            <span v-show="! searching">
                                {{ $t('products.no_products') }}
                            </span>
                                <span v-show="searching">
                                {{ $t('no_results') }}
                            </span>
                        </div>
                    </v-alert>
                </v-card-text>
            </v-card>
        </div>
    </div>
</template>

<script>
  import previewUpload from '../../../custom_components/previewUploader';

  import debounce from '../../../debounce';
  import infiniteScrollMixin from "../../../mixins/infinite_scroll";

  import { mapActions } from 'vuex';

  export default {
    name: "index",

    mixins: [infiniteScrollMixin],

    components: {
      previewUpload
    },

    data() {
      return {
        products: [],

        product: {
          title: '',
          description: '',
          price: '',
          category_id: '',
          image: null
        },

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

        table: {
          headers: [
            this.$t('products.table.headers.title'),
            this.$t('products.table.headers.price'),
            this.$t('products.table.headers.created_at'),
            this.$t('products.table.headers.updated_at'),
            ''
          ]
        },

        search: {
          keyword: '',
          created_at: null,
          display_created_at: new Date().toLocaleString('ru', {year: 'numeric', month: 'numeric', day: 'numeric', timezone: 'utc'}),
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
        resetPreview: false,

        isDestroying: false
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
       * Загрузка списка товаров
       *
       * @returns {Promise<void>}
       */
      async getProducts() {
        this.loading = true;

        try {
          const response = await axios.get(`${this.$attrs.apiRoute}/products`, {
            params: {
              page: this.pagination.page
            }
          });

          this.products = (this.pagination.page === 1)
              ? response.data.products.data
              : this.products.concat(response.data.products.data);

          this.pagination.last_page = response.data.products.last_page;
          this.pagination.total = response.data.products.total;
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message});
        } finally {
          this.loading = false;
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
       * Обработчик события debounce-поиска товаров
       */
      onSearch() {
        this.searchData(this);
      },

      /**
       * Поиск по товарам
       */
      searchData: debounce(vm => {
        axios.get(`${vm.$attrs.apiRoute}/products`, {
          params: {
            page: vm.page,
            keyword: vm.search.keyword,
            created_at: vm.search.created_at,
            category: vm.search.category
          }
        }).then(response => {
          vm.products = (vm.pagination.page === 1)
              ? response.data.products.data
              : vm.products.concat(response.data.products.data);

          vm.pagination.last_page = response.data.products.last_page;
          vm.pagination.total = response.data.products.total;
        }).catch(error => console.error(error));
      }, 300),

      /**
       * Удаление товара
       *
       * @param id
       * @returns {Promise<void>}
       */
      async remove(id) {
        try {
          const response = await axios.delete(`${this.$attrs.apiRoute}/products/${id}`);

          this.showNotification({ type: 'success', message: response.data.message});
          this.products = this.products.filter(product => product.id !== id);
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message});
        }
      },

      /**
       * Сохранение товара
       *
       * @returns {Promise<void>}
       */
      async save() {
        let formData = new FormData();

        for (const prop in this.product) {
          formData.append(prop, this.product[prop]);
        }

        try {
          const response = await axios.post(
              `${this.$attrs.apiRoute}/products`,
              formData,
              {
                headers: {
                  'Content-Type': 'multipart/form-data'
                }
              }
          );

          this.showNotification({ type: 'success', message: response.data.message});
          this.getProducts();

          this.resetForm();
        } catch (e) {
          this.setErrors(e.response.data.errors);
        }
      },

      /**
       * Закрыть модалку
       */
      cancel() {
        this.resetForm();
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

      /**
       * Инициализация компонента
       *
       * @returns {Promise<void>}
       */
      async initData() {
        this.loading = true;

        await this.loadCategories();

        await this.getProducts();

        this.loading = false;
      },

      /**
       * Обработчик события скролла в таблице
       */
      onScroll: function() {
        this.paginationScroll(this, $('.v-data-table__wrapper')[0], 'getProducts');
      },
    },

    watch: {
      'search': {
        handler: function(after, before) {
          if (after.category || after.created_at || after.keyword.length > 3) {
            this.pagination.page = 1;
            this.searching = true;

            this.searchData(this);
          } else if (! after.category || ! after.created_at || ! after.keyword.length) {
              this.pagination.page = 1;
              this.searching = true;

              this.getProducts();
          }
        },

        deep: true
      },

      'search.keyword': function (after, before) {
        if (after.length > 3) {
          this.onSearch();
        }
      },

      'search.display_created_at': function (after) {
        if (after === null) {
          this.search.publication_date = null;
        }
      },

      'search.created_at': function (after) {
        this.search.display_created_at = after.split('-').reverse().join('.');
      }
    },

    created() {
      this.initData();
    },

    mounted() {
      $('.v-data-table__wrapper')[0].addEventListener('scroll', this.onScroll);
    },

    destroyed() {
      $('.v-data-table__wrapper')[0].removeEventListener('scroll', this.onScroll);
    }
  }
</script>

<style scoped>

</style>