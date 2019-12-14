<template>
    <div>
        <v-card>
            <v-data-table :headers="table.headers"
                          :items="categories"
                          :items-per-page="15"
                          :item-key="'name'"
                          class="elevation-1"
                          :page.sync="pagination.page"
                          :search="'Search'"
                          :locale="$i18n.locale"
                          :noDataText="$t('v-data-table.no-data-text')"
                          :noResultsText="$t('v-data-table.no-results-text')"
                          :loading="loading"
                          multi-sort
                          :sort-by="['name']"
                          :sort-desc="[true]"
                          :page-text="'На странице'"
                          :loading-text="$t('v-data-table.loading-text')" :per-page="$t('v-data-table.per-page')"
                          :footer-props="{itemsPerPageText: 'На странице'}">
                <template v-slot:top>
                    <v-toolbar>
                        <v-toolbar-title>
                            {{ $t('categories.table.header')}}
                        </v-toolbar-title>

                        <v-divider class="mx-4" vertical inset></v-divider>
                        <v-spacer></v-spacer>

                        <v-text-field v-model="search.keyword"
                                      append-icon="search"
                                      label="Поиск"
                                      single-line>
                        </v-text-field>

                        <v-dialog v-model="modal" max-width="500px">
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
                                               @click="save()"
                                               :disabled="! form.valid">
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
                </template>
                <template v-slot:items="props">
                    <td>{{ props.item.name }}</td>
                </template>
                <template v-slot:category.action="{ category }">
<!--                    <v-icon-->
<!--                        small-->
<!--                        class="mr-2"-->
<!--                        @click="editItem(item)"-->
<!--                    >-->
<!--                        edit-->
<!--                    </v-icon>-->
                    <v-icon
                        small
                        @click="remove(category.id)"
                    >
                        delete
                    </v-icon>
                </template>
            </v-data-table>
        </v-card>
<!--        <div>-->
<!--            <v-btn color="success" dark  outlined @click="toRoute('create')">-->
<!--                <i class="pe-7s-plus"></i> Добавить-->
<!--            </v-btn>-->
<!--        </div>-->
    </div>
</template>

<script>
  import debounce from '../../../debounce';

  export default {
    name: "index",

    data() {
      return {
        categories: [],

        is_search: false,
        loading: false,

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
              v => v.length <= 25 || 'Длина не может превышать 25 символов',
              v => v.length > 0 || 'Поле обязательное к заполнению'
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

        modal: false
      }
    },

    computed: {
      createFormRoute() {
        return '/admin/categories/create';
      },

      editFormRoute() {
        return '/admin/categories/update';
      }
    },

    methods: {
      async loadData() {
        this.loading = true;
        const response = await axios.post('/admin/categories', {
          params: {
            page: this.pagination.page
          }
        });

        switch (response.data.status) {
          case 'error':
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            this.loading = false;
            break;
          case 'success':
            this.categories = [...response.data.categories.data];
            this.pagination.last_page = response.data.categories.last_page;
            this.loading = false;
            break;
        }
      },

      async save() {
        const response = await axios.post('/admin/categories/create', {
          ...this.category
        });

        switch (response.data.status) {
          case 'error':
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            break;
          case 'success':
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            this.loadData();
            this.modal = false;
            this.$refs.form.reset();
            break;
        }
      },

      async remove(id) {
        const response = await axios.post(`/admin/categories/delete/${id}`);

        switch (response.data.status) {
          case 'error':
            this.$swal('swal.title.error', response.data.msg, 'error');
            return false;

          case 'success':
            this.$swal('swal.title.success', response.data.msg, 'success');
            this.loadData();
        }
      },

      async searchData() {
        this.switchPage(1);
        this.loading = true;
        const response = await axios.get('/admin/categories/search', {
          params: {
            page: this.pagination.page,
            keyword: this.search.keyword,
            order_by: this.search.order_by
          }
        });

        switch (response.data.status) {
          case 'error':
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            this.loading = false;
            break;
          case 'success':
            this.categories = [...response.data.categories.data];
            this.pagination.last_page = response.data.categories.last_page;
            this.loading = false;
            break;
        }
      },

      close() {
        this.$refs.form.reset();
        this.modal = false;
      },

      toRoute(destination, id = null) {
        switch (destination) {
          case 'create':
            this.$router.push({ path: this.createFormRoute});
            break;

          case 'update':
            this.$router.push({ path: `${this.editFormRoute}/${id}`});
            break;
        }
      },

      switchPage(page) {
        this.pagination.page = page;
      }
    },

    watch: {
      'search.keyword': debounce(function (newVal) {
        this.searchData();
      }, 300)
    },

    mounted() {
      this.loadData();
    }
  }
</script>

<style scoped>

</style>
