<template>
    <div>
        <v-card>
            <v-toolbar>
                <v-toolbar-title>
                    {{ $t('categories.table.header')}}
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

                <v-dialog v-model="modal" max-width="500px" @click:outside="resetForm">
                    <template v-slot:activator="{on}">
                        <v-btn outlined color="success" class="" dark v-on="on">
                            <i class="pe-7s-plus"></i> {{ $t('categories.btn.add')}}
                        </v-btn>
                    </template>

                    <v-card>
                        <v-form v-model="form.valid" ref="form">
                            <v-card-title class="headline">
                                {{ $t('categories.form.header')}}
                            </v-card-title>

                            <v-card-text>
                                <errors></errors>
                                <v-text-field label="Название*"
                                              required
                                              v-model="category.name"
                                              clearable
                                              counter
                                              :rules="form.name.rules"
                                              maxlength="25">

                                </v-text-field>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>

                                <v-btn color="success"
                                       :disabled="! form.valid"
                                       @click="save()">
                                    {{ $t('categories.btn.save')}}
                                </v-btn>
                                <v-btn color="blue darken-1"
                                       text
                                       @click="close()">
                                    {{ $t('categories.btn.cancel') }}
                                </v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-card>
                </v-dialog>
            </v-toolbar>


            <v-card-text>
                <v-simple-table fixed-header :height="750" v-show="! loading && categories.length">
                    <template v-slot:default>
                        <thead>
                        <th v-for="header in table.headers" :key="header.text" class="text-left">
                            {{ header.text }}
                        </th>
                        </thead>
                        <tbody>
                        <tr v-for="category in categories" :key="category.id">
                            <td class="text-left">
                                {{ category.name }}
                            </td>
                            <td class="text-right">
                                <v-tooltip top color="primary">
                                    <template v-slot:activator="{ on }">
                                        <v-icon v-on="on"
                                                small
                                                @click="$router.push(`/admin/categories/${category.id}`)"
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
                                                @click="remove(category.id)"
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
                        <tfoot>
                        <tr>
                            <td></td>
                            <td class="text-right">
                                Всего: {{ categories.length }}
                            </td>
                        </tr>
                        </tfoot>
                    </template>
                </v-simple-table>

                <v-skeleton-loader type="table-row-divider@6" v-show="loading">

                </v-skeleton-loader>

                <v-alert color="info" outlined v-if="! loading && ! categories.length">
                    <div class="">
                        <span v-show="! searching">
                            Категории отсутствуют в системе
                        </span>
                        <span v-show="searching">
                            По Вашему запросу ничего не найдено
                        </span>
                    </div>
                </v-alert>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
  import debounce from '../../../debounce';
  // import Category from '../../../store/models/category';

  // import { mapGetters } from 'vuex';
  import { mapActions } from 'vuex';

  export default {
    name: "index",

    data() {
      return {
        categories: [],

        searching: false,

        search: {
          keyword: '',
          order_by: ''
        },

        category: {
          name: ''
        },

        form: {
          valid: false,
          name: {
            rules: [
              v => (v !== undefined && v.length <= 25) || 'Длина не может превышать 25 символов',
              v => v !== '' || 'Поле обязательное к заполнению'
            ]
          }
        },

         table: {
            headers:[
              {
                text: ' Название',
                align: 'left',
                sortable: true,
                value: 'name'
              },
              {

              }
            ]
         },

        pagination: {
          last_page: 1,
          page: 1
        },

        modal: false,
        loading: false
      }
    },

    computed: {
      createFormRoute() {
        return '/admin/categories/create';
      },

      editFormRoute() {
        return '/admin/categories/update';
      },

      // ...mapGetters({
      //   'categories': 'category/categories',
      //   'loading':    'category/loading'
      // })
    },

    methods: {
      ...mapActions('errors', {
        'resetErrors': 'resetErrors',
        'setErrors': 'setErrors'
      }),
      /**
       * Загрузка списка категорий
       */
      // async getCategories() {
      //   this.$store.dispatch('category/getCategories');
      // },
      async getCategories() {
        this.loading = true;

        try {
          const response = await axios.get(`${this.$attrs.apiRoute}/categories`, {
            params: {
              page: this.pagination.page
            }
          });

          this.categories = response.data.categories;
        } catch (e) {
          this.$swal(this.$t('swal.title.error'), e.response.data.msg, 'error');
        } finally {
          this.loading = false;
        }
      },

      /**
       * Сохранение категории
       *
       * @returns {Promise<void>}
       */
      async save() {
        try {
          const response = await axios.post(`${this.$attrs.apiRoute}/categories`, {
            ...this.category
          });

          this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
          this.getCategories();
          this.resetForm();
        } catch (e) {
          this.setErrors(e.response.data.errors);
        }
      },

      /**
       * Удаление категории
       *
       * @param id
       * @returns {Promise<boolean>}
       */
      async remove(id) {
        try {
          const response = await axios.delete(`${this.$attrs.apiRoute}/categories/${id}`);
          this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
          this.categories = this.categories.filter(category => category.id !== id);
        } catch (e) {
          this.$swal(this.$t('swal.title.error'), e.response.data.msg, 'error');
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
       * Обработчик события debounce-поиска категорий
       */
      onSearch() {
        if (this.search.keyword.length < 3) {
          return ;
        }

        this.searchData(this);
      },

      /**
       * Поиск по категориям
       */
      searchData: debounce((vm) => {
        axios.get(`${vm.$attrs.apiRoute}/categories`, {
          params: {
            page: vm.pagination.page,
            keyword: vm.search.keyword
          }
        }).then(response => {
          vm.categories = response.data.categories;
          vm.pagination.last_page = response.data.categories.last_page;
        }).catch(error => {
          vm.$swal(vm.$t('swal,title.error'), error.data.msg, 'error');
        });
      }, 300),

      /**
       * Закрытие формы
       */
      close() {
        this.resetForm();
      },
    },

    watch: {
      'search.keyword': function (after, before) {
        if (after.length) {
          this.pagination.page = 1;
          this.onSearch();
        } else {
          this.pagination.page = 1;
          this.getCategories();
        }
      }
    },

    created() {
      this.getCategories();
    }
  }
</script>