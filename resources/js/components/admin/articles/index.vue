<template>
    <div>
        <v-card>
            <v-toolbar>
                <!-- TODO вынести в компонент articles-search -->
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

                <v-menu ref="search-calendar-menu"
                        v-model="calendar"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        max-width="290px"
                        min-width="200px">
                    <template v-slot:activator="{ on }">
                        <v-text-field
                                @click="calendar = true"
                                v-model="search.display_publication_date"
                                label="Дата публикации"
                                prepend-icon="event"
                                hint="Дата публикации"
                                dense
                                readonly
                                clearable
                                persistent-hint
                                single-line

                        ></v-text-field>
                    </template>

                    <v-date-picker :locale="$i18n.locale"
                                   width="290"
                                   @input="calendar = false"
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
                </v-menu>

                <v-spacer></v-spacer>

                <v-checkbox v-model="search.is_publicated" label="Публикация"></v-checkbox>

                <v-spacer></v-spacer>

                <!-- TODO вынести в компонент articles-form-create -->
                <v-dialog v-model="modal" max-width="1000px" persistent>
                    <template v-slot:activator="{on}">
                        <v-btn color="success" outlined v-on="on" @click="resetPreview = false">
                            <i class="pe-7s-plus"></i> {{ $t('articles.btn.add') }}
                        </v-btn>
                    </template>

                    <v-card>
                        <v-form v-model="form.valid" ref="form">
                            <v-card-title>
                                {{ $t('articles.form.header')}}
                            </v-card-title>
                            <v-card-text>
                                <errors :errors="errors"></errors>
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
                                        <v-menu ref="form-calendar-menu"
                                                v-model="menu"
                                                :close-on-content-click="false"
                                                transition="scale-transition"
                                                offset-y
                                                max-width="290px"
                                                min-width="290px">
                                            <template v-slot:activator="{ on }">
                                                <v-text-field
                                                        v-on="on"
                                                        v-model="article.display_publication_date"
                                                        :label="$t('articles.form.labels.publication_date')"
                                                        prepend-icon="event"
                                                        readonly
                                                ></v-text-field>
                                            </template>

                                            <v-date-picker v-model="article.publication_date"
                                                           :min="new Date().toISOString().substr(0,10)"
                                                           @input="menu = false"
                                                           no-title
                                                           first-day-of-week="1"
                                                           scrollable
                                                           :locale="$i18n.locale">

                                                <v-spacer></v-spacer>

                                                <v-btn color="blue darken-1" text @click="menu = false">
                                                    {{ $t('articles.btn.cancel') }}
                                                </v-btn>

                                                <v-btn color="primary" outlined @click="menu = false">OK</v-btn>

                                            </v-date-picker>
                                        </v-menu>
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
                                        <preview-upload @uploaded="onUploadImage" :reset="resetPreview"></preview-upload>
                                    </v-col>
                                </v-row>


                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="success"
                                       :disabled="! form.valid || ! article.image"
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
                <!-- TODO вынести в компонент articles-list -->
                <v-simple-table :fixed-header=" ! calendar" :height="750" v-show="! loading && articles.length" ref="table">
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

                <v-alert color="info" outlined v-if="! loading && ! articles.length">
                    <div class="">
                        <span v-show="! searching">
                            Новости отсутствуют в системе
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
          publication_date: null,
          display_publication_date: new Date().toLocaleString('ru', {year: 'numeric', month: 'numeric', day: 'numeric', timezone: 'utc'}),
          is_publicated: Number(false)
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
        calendar: false,
        resetPreview: false,

        errors: []
      }
    },

    methods: {
      /**
       * Обработчик события загрузки картинки статьи
       */
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

        try {
          const response = await axios.get(`${this.$attrs.apiRoute}/articles`, {
            params: {
              page: this.pagination.page
            }
          });

          this.articles = (this.pagination.page === 1) ? response.data.articles.data : this.articles.concat(response.data.articles.data);
          this.pagination.total = response.data.articles.total;
          this.pagination.last_page = response.data.articles.last_page;
          this.loading = false;
        } catch (e) {
          this.$swal(this.$t('swal.title.error'), e.response.data.msg, 'error');
        }
      },

      /**
       * Удаление статьи
       *
       * @param id
       * @returns {Promise<boolean>}
       */
      async remove(id) {
        try {
          const response = await axios.delete(`${this.$attrs.apiRoute}/articles/${id}`);
          this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
          this.articles = this.articles.filter((article) => article.id !== id);
        } catch (e) {
          this.$swal(this.$t('swal.title.error'), e.response.data.msg, 'error');
        }
      },

      /**
       * Обработчик события для debounce-поиска
       */
      onSearch() {
        this.searchData(this);
      },

      /**
       * Поиск статей
       */
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

        try {
          const response = await axios.post(
              `${this.$attrs.apiRoute}/articles`,
              formData,
              {
                headers: {
                  'Content-Type': 'multipart/form-data'
                }
              }
          );

          this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
          this.loadData();

          this.resetForm();
        } catch (e) {
          this.errors = e.response.data.errors;
        }
      },

      /**
       * Сброс формы
       *
       */
      async resetForm() {
        this.modal = false;
        this.article.image = null;
        this.resetPreview = true;
        this.$refs.form.reset();
      },

      /**
       * Закрытие модального окна
       *
       */
      cancel() {
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
       * отслеживание форматирования даты публикации в модальном окне
       */
      'article.publication_date': function (after, before) {
        this.article.display_publication_date = after.split('-').reverse().join('.');
      },

      /**
       * отслеживание поиска в целом
       *
       */
      'search': {
        handler: function(after, before) {
          if (after.keyword.length > 3 || after.publication_date || after.is_publicated) {
            this.pagination.page = 1;
            this.searching = true;

            this.searchData(this);
          } else if(! after.keyword.length || ! after.publication_date || ! after.is_publicated) {
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
      },

      'search.is_publicated': function (after) {
        this.search.is_publicated = Number(after);
      },

      /**
       * отслеживание форматирования даты публикации в  поисковой форме
       */
      'search.publication_date': function (after, before) {
        this.search.display_publication_date = after.split('-').reverse().join('.');
      },

      'search.display_publication_date': function (after, before) {
        if (after === null) {
          this.search.publication_date = null;
        }
      },
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
