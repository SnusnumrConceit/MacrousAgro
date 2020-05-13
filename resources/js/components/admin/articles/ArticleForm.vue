<template>
    <div>
        <v-card v-show="! loading">
            <v-form v-model="form.valid" ref="form">
                <v-card-title>
                    {{ $t('articles.form.header')}}
                </v-card-title>

                <v-card-text>
                    <errors />
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
                                    v-model="calendar"
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
                                               @input="calendar = false"
                                               no-title
                                               first-day-of-week="1"
                                               scrollable
                                               :locale="$i18n.locale">

                                    <v-spacer />

                                    <v-btn color="blue darken-1" text @click="calendar = false">
                                        {{ $t('buttons.cancel') }}
                                    </v-btn>

                                    <v-btn color="primary" outlined @click="calendar = false">OK</v-btn>

                                </v-date-picker>
                            </v-menu>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col>
                            <label class="d-flex pl-1 v-label v-label--active theme--light tiptap__form-label">
                                {{ $t('articles.form.labels.description') }}
                            </label>

                            <tiptap :content="article.description" v-if="! loading" @description-changed="syncDescription"/>
                            <!--<v-textarea v-model="article.description"-->
                                        <!--:label="$t('articles.form.labels.description')"-->
                                        <!--required-->
                                        <!--counter-->
                                        <!--maxlength="2000"-->
                                        <!--:rules="form.description.rules">-->
                            <!--</v-textarea>-->

                            <v-checkbox v-model="article.is_publicated"
                                        :label="$t('articles.form.labels.is_publicated')">
                            </v-checkbox>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col>
                            <preview-upload @uploaded="onUploadImage" :reset="resetPreview" :src="article.src" v-if="! loading"/>
                        </v-col>
                    </v-row>


                </v-card-text>

                <v-card-actions>
                    <v-spacer v-if="! id"/>

                    <v-btn color="success"
                           :disabled="isValid"
                           outlined
                           @click="id ? update() : create()">
                        {{ $t(btnSave) }}
                    </v-btn>

                    <v-btn color="blue darken-1"
                           text
                           @click="id ? $router.back() : hide()">
                        {{ $t(btnCancel) }}
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>

        <v-skeleton-loader type="card" v-show="loading" />
    </div>
</template>

<script>
  import {mapActions} from 'vuex';
  import Tiptap from '../../../custom_components/tiptap';
  import previewUpload from '../../../custom_components/previewUploader';

  export default {
    name: "ArticleForm",

    components: {
      previewUpload,
      Tiptap
    },

    computed: {
      id() {
        return this.$route.params.id;
      },

      btnCancel() {
        return this.id ? 'buttons.back' : 'buttons.cancel';
      },

      btnSave() {
        return this.id ? 'buttons.edit' : 'buttons.save';
      },

      isValid() {
        return ! this.form.valid || (this.id ? false : ! this.article.image);
      }
    },

    data() {
      return {
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

        loading: false,
        calendar: false,
        resetPreview: false
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
       * Загрузка статьи для редактирования
       *
       * @returns {Promise<void>}
       */
      async getArticle() {
        try {
          const response = await axios.get(`/articles/${this.id}/edit`);
          this.article = response.data.article;
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message});
        }
      },

      /**
       * Обработчик события загрузки картинки статьи
       */
      onUploadImage(image) {
        this.article.image = image;
      },

      /**
       * Создание статьи
       *
       * @returns {Promise<void>}
       */
      async create() {
        let formData = new FormData();

        for (const prop in this.article) {
          formData.append(prop, (typeof this.article[prop] == 'boolean') ? Number(this.article[prop]) : this.article[prop]);
        }

        try {
          const response = await axios.post(
              `/articles`,
              formData,
              {
                headers: {
                  'Content-Type': 'multipart/form-data'
                }
              }
          );

          this.showNotification({ type: 'success', message: response.data.message});

          this.$root.$emit('article-created');
          this.hide();
        } catch (e) {
          this.setErrors(e.response.data.errors);
        }
      },

      /**
       * Обновление изменений статьи
       *
       * @returns {Promise<void>}
       */
      async update() {
        const formData = new FormData();

        for (const prop in this.article) {
          formData.append(prop, (typeof this.article[prop] === 'boolean') ? Number(this.article[prop]) : this.article[prop]);
        }

        formData.append('_method', 'PATCH');

        try {
          const response = await axios.post(
              `/articles/${this.id}`,
              formData, {
                headers: {
                  'Content-Type': 'multipart/form-data'
                }
              });
          this.showNotification({ type: 'success', message: response.data.message});
          this.$router.back();
        } catch (e) {
          this.setErrors(e.response.data.errors);
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
        this.resetErrors();
      },

      /**
       * Закрытие модального окна
       *
       */
      hide() {
        this.resetForm();
        this.$emit('hide-modal');
      },

      /**
       * Инициализация компонента
       *
       * @returns {Promise<void>}
       */
      async initData() {
        this.loading = true;

        await this.getArticle();

        this.loading = false;
      },

      syncDescription(data) {
        console.log(data);
        this.article.description = data;
      }
    },

    watch: {
      /**
       * отслеживание форматирования даты публикации в модальном окне
       */
      'article.publication_date': function (after, before) {
        this.article.display_publication_date = after.split('-').reverse().join('.');
      }
    },

    created() {
      if (this.id) {
        this.initData();
      }
    }
  }
</script>