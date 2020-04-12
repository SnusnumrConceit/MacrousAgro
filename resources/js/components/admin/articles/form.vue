<template>
    <div>
        <v-card v-show="! loading">
            <v-form ref="form" v-model="form.valid">
                <v-card-title>
                    {{ article.title}}
                </v-card-title>
                <v-card-text>
                    <errors :errors="errors"></errors>
                    <v-row>
                        <v-col>
                            <preview-upload @uploaded="onUploadImage" :src="article.src" ref="previewUpload" v-if="! loading"></preview-upload>
                        </v-col>
                        <v-col>
                            <v-text-field v-model="article.title"
                                          :label="$t('articles.form.labels.title')"
                                          required
                                          counter
                                          clearable
                                          maxlength="255"
                                          :rules="form.title.rules">
                            </v-text-field>

                            <!--:return-value.sync="article.publication_date"-->
                            <v-menu   ref="calendar-menu"
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
                                               no-title
                                               first-day-of-week="1"
                                               @input="menu = false"
                                               :locale="$i18n.locale">
                                    <v-spacer></v-spacer>
                                    <v-btn color="blue darken-1" @click="menu = false" text>
                                        {{ $t('articles.btn.cancel') }}
                                    </v-btn>
                                    <v-btn color="primary" outlined @click="menu = false">OK</v-btn>
                                </v-date-picker>
                            </v-menu>

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
                        {{ $t('articles.btn.delete')}}
                    </v-btn>
                    <v-btn color="default" outlined @click="goBack">
                        {{ $t('articles.btn.back')}}
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
        article: {
          title: '',
          description: '',
          image: null,
          publication_date: '',
          is_published: true,
          src: ''
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
        menu: false,

        errors: []
      }
    },

    computed: {
      id() {
        return this.$route.params.id;
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
       * Сохранение изменений статьи
       *
       * @returns {Promise<void>}
       */
      async save() {
        const formData = new FormData();

        for (const prop in this.article) {
            formData.append(prop, (typeof this.article[prop] === 'boolean') ? Number(this.article[prop]) : this.article[prop]);
        }

        formData.append('_method', 'PATCH');

        try {
          const response = await axios.post(
              `${this.$attrs.apiRoute}/articles/${this.id}`,
              formData, {
                headers: {
                  'Content-Type': 'multipart/form-data'
                }
              });
          this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
          this.$router.push({name: 'Articles'});
        } catch (e) {
          this.errors = e.response.data.errors;
        }
      },

      /**
       * Удаление статьи
       *
       * @returns {Promise<void>}
       */
      async remove() {
        try {
          const response = await axios.delete(`${this.$attrs.apiRoute}/articles/${this.id}`);
          this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
          this.goBack();
        } catch (e) {
          this.$swal(this.$t('swal.title.error'), e.response.data.msg, 'error');
        }
      },

      /**
       * Загрузка статьи для редактирования
       *
       * @returns {Promise<void>}
       */
      async loadData() {
        try {
          const response = await axios.get(`${this.$attrs.apiRoute}/articles/${this.id}/edit`);
          this.article = response.data.article;
        } catch (e) {
          this.$swal('swal.title.error', e.response.data.msg);
        }
      },

      /**
       * Назад
       */
      goBack() {
        this.$router.go(-1);
      },

      /**
       * Инициализация компонента
       *
       * @returns {Promise<void>}
       */
      async initData() {
        this.loading = true;

        await this.loadData();

        this.loading = false;
      }
    },

    watch: {
      'article.publication_date': function (after, before) {
        this.article.display_publication_date = after.split('-').reverse().join('.');
      }
    },

    created() {
      this.initData();
    }
  }
</script>

<style scoped>

</style>
