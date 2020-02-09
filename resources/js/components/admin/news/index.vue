<template>
    <div>
        <v-card>
            <v-data-table :headers="table.headers"
                          :items="articles"
                          :items-per-page="15"
                          class="elevation-1"
                          :page="pagination.page"
                          :search="'Search'"
                          :locale="$i18n.locale"
                          :noDataText="$t('v-data-table.no-data-text')"
                          :noResultsText="$t('v-data-table.no-results-text')"
                          loading
                          multi-sort
                          :sort-by="['title']"
                          :sort-desc="[true]"
                          :loading-text="$t('v-data-table.loading-text')" :per-page="$t('v-data-table.per-page')"
                          :footer-props="{itemsPerPageText: 'На странице'}">
                 <template v-slot:top>
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
                                                     <v-btn flat color="blue darken-1" @click="menu = false" text>
                                                         {{ $t('articles.btn.cancel') }}
                                                     </v-btn>
                                                     <v-btn flat color="primary" outline @click="menu = false">OK</v-btn>
                                                 </v-date-picker>

<!--                                                 <v-menu-->
<!--                                                     ref="menu"-->
<!--                                                     :close-on-content-click="false"-->
<!--                                                     v-model="menu"-->
<!--                                                     :nudge-right="40"-->
<!--                                                     :return-value.sync="article.publication_date"-->
<!--                                                     transition="scale-transition"-->
<!--                                                     offset-y-->
<!--                                                     full-width-->
<!--                                                     min-width="290px"-->
<!--                                                 >-->

<!--  -->
<!--                                                 </v-menu>-->

<!--                                                 <v-text-field v-model="article.publication_date"-->
<!--                                                               :label="$t('articles.form.labels.publication_date')"-->
<!--                                                               readonly-->
<!--                                                               :rules="form.publication_date.rules">-->
<!--                                                 </v-text-field>-->

<!--                                                 <v-date-picker v-model="article.publication_date" scrollable>-->
<!--                                                     <v-spacer></v-spacer>-->
<!--                                                     <v-btn flat color="primary" @click="calendar_modal = false">-->
<!--                                                         {{ $t('articles.btn.cancel') }}-->
<!--                                                     </v-btn>-->
<!--                                                     <v-btn flat color="primary" @click="">OK</v-btn>-->
<!--                                                 </v-date-picker>-->
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
                                                 <vue-dropzone ref="dropzone" id="dropzone" :options="form.dropzone_options"></vue-dropzone>
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
                 </template>

            </v-data-table>
        </v-card>
    </div>
</template>

<script>
  import vue2Dropzone from 'vue2-dropzone';
  import 'vue2-dropzone/dist/vue2Dropzone.min.css';

  export default {
    name: "index",

    components: {
      vueDropzone: vue2Dropzone
    },

    data() {
      return {
        articles: [],

        is_search: false,

        search: {
          keyword: '',
          order_by: '',
          filter: {
            publication_date: ''
          }
        },

        article: {
          title: '',
          description: '',
          publication_date: new Date().toISOString().substr(0,10),
          is_publicated: true
        },

        form: {
          valid: false,
          title: {
            rules: [
              v => v.length > 0 || this.$t('articles.form.rules.title.required'),
              v => v.length <= 255 || this.$t('articles.form.rules.title.length', {length: 255})
            ],
          },

          description: {
            rules: [
              v => v.length > 0 || this.$t('articles.form.rules.description.required'),
              v => v.length <= 2000 || this.$t('articles.form.rules.title.length', {length: 2000})
            ]
          },

          publication_date: {
            rules: [
              v => v.length > 0 || this.$t('articles.form.rules.publication_date.required'),
              v => v > Date.now || this.$t('articles.form.rules.publication_date.length'),
            ]
          },

          dropzone_options: {
            url: 'https://httpbin.org/post',
            thumbnailWidth: 150,
            maxFilesize: 0.5,
            headers: { "X-CSRF-TOKEN": $('meta').attr('content') },

            "dictDefaultMessage": this.$t('dropzone.dictDefaultMessage.articles'),

            "dictFallbackMessage":          this.$t('dropzone.dictFallbackMessage'),
            "dictResponseError":            this.$t('dropzone.dictResponseError'),
            "dictInvalidFileType":          this.$t('dropzone.dictInvalidFileType'),
            "dictFileTooBig":               this.$t('dropzone.dictFileTooBig'),
            "dictCancelUpload":             this.$t('dropzone.dictCancelUpload'),
            "dictUploadCanceled":           this.$t('dropzone.dictUploadCanceled'),
            "dictCancelUploadConfirmation": this.$t('dropzone.dictCancelUploadConfirmation'),
            "dictRemoveFile":               this.$t('dropzone.dictRemoveFile'),
            "dictRemoveFileConfirmation":   this.$t('dropzone.dictRemoveFileConfirmation'),
          }

        },

        table: {
          headers: [
            'Заголовок',
            'Опубликована',
            'Последнее изменение'
          ]
        },

        pagination: {
          last_page: 1,
          page: 1
        },

        modal: false,
        menu: false
      }
    },

    methods: {
      async loadData() {
        const response = await axios.get('/admin/articles');

        if (response.data.status === 'error') {
          this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
          return false;
        }

        this.$nextTick(() => {
          this.articles = response.data.articles.data;
          this.pagination.last_page = response.data.articles.last_page;
        });
      },

      async remove(id) {
        const response = await axios.delete(`/admin/articles/${id}`);

        switch (response.data.status) {
          case 'error':
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            return false;

          case 'success':
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            this.loadData();
        }
      },

      async searchData() {
        this.switchPage(1);
        const response = await axios.get('/admin/articles/search', {
          params: {
            page: this.pagination.page,
            keyword: this.search.keyword,
            order_by: this.search.order_by,
            filter: {...this.search.filter}
          }
        });

        if (response.data.status === 'error') {
          this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
          return false;
        }

        this.$nextTick(() => {
          this.articles = response.data.articles.data;
          this.pagination.last_page = response.data.articles.last_page;
        });
      },

      async save() {
        const response = await axios.post('/admin/articles', {
          ...this.article
        });

        switch (response.data.status) {
          case 'error':
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            break;

          case 'success':
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            this.loadData();
            this.$refs.form.reset();
            this.modal = false;
            break;
        }
      },

      cancel() {
        this.$refs.form.reset();
        this.modal = false;
      }
    },

    created() {
      this.loadData();
    }
  }
</script>

<style scoped>

</style>
