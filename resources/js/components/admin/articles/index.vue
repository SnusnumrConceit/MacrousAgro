<template>
    <div>
        <v-card>
            <v-toolbar>
                <v-toolbar-title>
                    {{ $t('articles.table.header') }}
                </v-toolbar-title>

                <v-divider class="mx-4" vertical inset></v-divider>

                <v-spacer></v-spacer>

                <v-text-field v-model="search.keyword" append-icon="search"
                              label="Поиск"
                              single-line>
                </v-text-field>

                <v-spacer></v-spacer>

                <v-text-field
                        @click="calendar = true"
                        v-model="search.publication_date"
                        label="Дата публикации"
                        prepend-icon="event"
                        hint="Формат даты: ГГГГ-ММ-ДД"
                        dense
                        readonly
                        clearable
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
                               v-model="search.publication_date">
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" @click="calendar = false" text>
                        {{ $t('users.btn.cancel') }}
                    </v-btn>
                    <v-btn color="primary" outlined @click="calendar = false">OK</v-btn>
                </v-date-picker>

                <v-spacer></v-spacer>

                <v-dialog v-model="modal" max-width="1000px">
                    <template v-slot:activator="{on}">
                        <v-btn color="success" outlined v-on="on">
                            <i class="pe-7s-plus"></i> {{ $t('articles.btn.add') }}
                        </v-btn>
                    </template>

                    <v-card>
                        <v-form v-model="form.valid" ref="form">
                            <v-card-title>
                                {{ $t('articles.form.header')}}
                            </v-card-title>
                            <v-card-text>
                                <v-row>
                                    <v-col cols="6">
                                        <v-text-field v-model="article.title"
                                                      :label="$t('articles.form.labels.title')"
                                                      required
                                                      counter
                                                      clearable
                                                      maxlength="255"
                                                      :rules="form.title.rules">
                                        </v-text-field>
                                    </v-col>
                                    <v-col>

                                        <v-text-field
                                                @click="menu = true"
                                                v-model="article.publication_date"
                                                :label="$t('articles.form.labels.publication_date')"
                                                prepend-icon="event"
                                                readonly
                                        ></v-text-field>

                                        <v-date-picker v-model="article.publication_date"
                                                       :min="new Date().toISOString().substr(0,10)"
                                                       :style="{position: 'absolute', right: '20%', 'z-index': 3}"
                                                       no-title
                                                       scrollable
                                                       v-if="menu"
                                                       :locale="$i18n.locale">
                                            <v-spacer></v-spacer>
                                            <v-btn color="blue darken-1" @click="menu = false" text>
                                                {{ $t('articles.btn.cancel') }}
                                            </v-btn>
                                            <v-btn color="primary" outline @click="menu = false">OK</v-btn>
                                        </v-date-picker>
                                    </v-col>
                                </v-row>

                                <v-row>
                                    <v-col>
                                        <v-textarea v-model="article.description"
                                                    :label="$t('articles.form.labels.description')"
                                                    required
                                                    counter
                                                    maxlength="2000"
                                                    :rules="form.description.rules">

                                        </v-textarea>

                                        <v-checkbox v-model="article.is_publicated"
                                                    :label="$t('articles.form.labels.is_publicated')">
                                        </v-checkbox>
                                    </v-col>
                                </v-row>

                                <v-row>
                                    <v-col>
                                        <!--<vue-dropzone ref="article_dropzone" id="dropzone" :options="dropzone_options"></vue-dropzone>-->
                                        <preview-upload @uploaded="onUploadImage"></preview-upload>
                                    </v-col>
                                </v-row>


                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="success"
                                       :disabled="! form.valid"
                                       outlined
                                       @click="save()">
                                    {{ $t('articles.btn.save') }}
                                </v-btn>
                                <v-btn color="blue darken-1"
                                       text
                                       @click="cancel()">
                                    {{ $t('articles.btn.cancel') }}
                                </v-btn>
                            </v-card-actions>

                        </v-form>
                    </v-card>

                </v-dialog>

            </v-toolbar>

            <v-card-text>
                <v-simple-table :fixed-header=" ! calendar" :height="750" v-show="! loading" ref="table">
                    <template v-slot:default>
                        <thead>
                        <th v-for="header in table.headers" :key="header" class="text-left">
                            {{ header }}
                        </th>
                        </thead>
                        <tbody>
                        <tr v-for="article in articles" :key="article.id">
                            <td class="text-left">
                                {{ article.title }}
                            </td>
                            <td class="text-left">
                                {{ article.formatted_publication_date }}
                            </td>
                            <td class="text-left">
                                {{ article.formatted_updated_at }}
                            </td>
                            <td class="text-right">
                                <v-tooltip top color="primary">
                                    <template v-slot:activator="{ on }">
                                        <v-icon v-on="on"
                                                small
                                                @click="$router.push(`/admin/articles/${article.id}`)"
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
                                                @click="remove(article.id)"
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
                    </template>

                </v-simple-table>
                <span v-show="! loading" class="d-flex flex-row-reverse">
                    Всего: {{ pagination.total }}
                </span>

                <v-skeleton-loader type="table-row-divider@6" v-show="loading">

                </v-skeleton-loader>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
  import previewUpload from '../../../custom_components/previewUploader';

  import debounce from '../../../debounce';

  import infiniteScrollMixin from '../../../mixins/infinite_scroll';

  export default {
    name: "index",

    components: {
      previewUpload
    },

    mixins: [infiniteScrollMixin],

    data() {
      return {
        articles: [],

        loading: false,
        searching: false,

        search: {
          keyword: '',
          publication_date: null
        },

        article: {
          title: '',
          description: '',
          publication_date: new Date().toISOString().substr(0,10),
          is_publicated: false,
          image: null
        },

        form: {
          valid: false,

          title: {
            rules: [
              v => v !== '' || this.$t('articles.form.rules.title.required'),
              v => (v !== undefined && v !== null && v.length <= 255) || this.$t('articles.form.rules.title.length', {length: 255})
            ],
          },

          description: {
            rules: [
              v => v !== '' || this.$t('articles.form.rules.description.required'),
              v => (v !== undefined && v !== null && v.length <= 2000) || this.$t('articles.form.rules.title.length', {length: 2000})
            ]
          },

          publication_date: {
            rules: [
              v => v !== '' || this.$t('articles.form.rules.publication_date.required'),
              v => (v !== undefined && v !== null && v > Date.now) || this.$t('articles.form.rules.publication_date.length'),
            ]
          },
        },

        table: {
          headers: [
            'Заголовок',
            'Опубликована',
            'Последнее изменение',
            ''
          ]
        },

        pagination: {
          last_page: 1,
          page: 1,
          total: ''
        },

        modal: false,
        menu: false,
        calendar: false
      }
    },

    methods: {
      /** **/
      onUploadImage(image) {
        this.article.image = image;
      },


      /**
       * Инициализация данных для компонента
       *
       */
      async initData() {
        await this.loadData();
      },

      /**
       * Загрузка списка статей
       *
       * @returns {Promise<boolean>}
       */
      async loadData() {
        this.loading = true;
        const response = await axios.get(`${this.$attrs.apiRoute}/articles`, {
          params: {
            page: this.pagination.page
          }
        });

        if (response.data.status === 'error') {
          this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
          return false;
        }

        console.log(response.data);
        this.articles = (this.pagination.page === 1) ? response.data.articles.data : this.articles.concat(response.data.articles.data);
        this.pagination.total = response.data.articles.total;
        this.pagination.last_page = response.data.articles.last_page;
        this.loading = false;
      },

      /**
       * Удаление статьи
       *
       * @param id
       * @returns {Promise<boolean>}
       */
      async remove(id) {
        const response = await axios.delete(`${this.$attrs.apiRoute}/articles/${id}`);

        switch (response.data.status) {
          case 'error':
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            return false;

          case 'success':
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            this.loadData();
        }
      },

      /**
       * Обработчик события для debounce-поиска
       */
      onSearch() {
        this.searchData(this);
      },

      searchData: debounce(vm => {
        axios.get(`${vm.$attrs.apiRoute}/articles`, {
          params: {
            page: vm.page,
            ...vm.search
          }
        }).then(response => {
          vm.articles = (vm.pagination.page === 1) ? response.data.articles.data : vm.articles.concat(response.data.articles.data);
          vm.pagination.last_page = response.data.articles.last_page;
          vm.pagination.total = response.data.articles.total;
        }).catch(error => console.error(error));
      }, 300),

      /**
       * Сохранение статьи
       *
       * @returns {Promise<void>}
       */
      async save() {
        let formData = new FormData();

        for (const prop in this.article) {
          formData.append(prop, (typeof this.article[prop] == 'boolean') ? Number(this.article[prop]) : this.article[prop]);
        }

        const response = await axios.post(
            `${this.$attrs.apiRoute}/articles`,
            formData,
            {
              headers: {
                'Content-Type': 'multipart/form-data'
              }
            }
        );

        switch (response.data.status) {
          case 'error':
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            return ;

          case 'success':
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            this.loadData();
            // this.$refs.form.reset();
            this.modal = false;
            break;
        }

        this.resetForm();
      },

      /**
       * Сброс формы
       *
       * @returns {Promise<void>}
       */
      async resetForm() {
        this.article.image = null;
        this.$refs.form.reset();
      },

      /**
       * Закрытие модального окна
       *
       */
      cancel() {
        this.modal = false;
        this.resetForm();
      },

      /**
       * Обработчик события для скролловой пагинации
       *
       */
      onScroll: function() {
        this.paginationScroll(this, $('.v-data-table__wrapper')[0]);
      },
    },

    watch: {
      /**
       * отслеживание поиска в целом
       *
       */
      'search': {
        handler: function(after, before) {
          if (after.publication_date || after.keyword.length > 3) {
            this.pagination.page = 1;
            this.searching = true;

            this.searchData(this);
          } else if(! after.publication_date || after.keyword.length === 0) {
            this.pagination.page = 1;
            this.searching = true;

            this.loadData();
          }
        },

        deep: true
      },

      /**
       * отслеживание поискового ввода
       *
       */
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
      $('.v-data-table__wrapper')[0].addEventListener('scroll', this.onScroll);
    },

    beforeDestroy() {
      this.isDestroying = true;
    }
  }
</script>

<style scoped>

</style>
