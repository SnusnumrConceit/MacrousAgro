<template>
    <div>
        <v-card>
            <v-toolbar>
                <v-toolbar-title>
                    {{ $t('videos.header')}}
                </v-toolbar-title>

                <v-divider class="mx-4" vertical inset></v-divider>
                <v-spacer></v-spacer>

                <v-text-field v-model="search.keyword" append-icon="search"
                              label="Поиск"
                              single-line>
                </v-text-field>

                <v-dialog v-model="modal" max-width="500px">
                    <template v-slot:activator="{on}">
                        <v-btn color="success" v-on="on" outlined>
                            <i class="pe-7s-plus"></i>
                            {{ $t('videos.btn.add')}}
                        </v-btn>
                    </template>
                    <v-card>
                        <v-form v-model="form.valid">
                            <v-card-title>
                                Видео
                            </v-card-title>
                            <v-card-text>
                                <v-text-field v-model="video.title"
                                              :label="$t('videos.form.labels.title')"
                                              counter
                                              clearable
                                              maxlength="255"
                                              :rules="form.title.rules">

                                </v-text-field>

                                <vue-dropzone ref="dropzone" id="dropzone" :options="dropzone_options"></vue-dropzone>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>

                                <v-btn color="success" outlined :disabled="!form.valid" @click="save()">
                                    {{ $t('videos.btn.save')}}
                                </v-btn>
                                <v-btn color="blue darken-1" @click="cancel()" text>
                                    {{ $t('videos.btn.cancel')}}
                                </v-btn>
                            </v-card-actions>

                        </v-form>
                    </v-card>
                </v-dialog>
            </v-toolbar>

            <v-card-text>
                <v-row>
                    <v-col col="6" v-for="(video, index) in videos" :key="video.id">
                        <v-card>
                            <v-card-title>
                                {{ video.title }}
                            </v-card-title>
                            <v-card-text>
                                <video :src="video.url"></video>
                            </v-card-text>
                            <v-card-actions>
                                <v-btn color="danger" outlined @click="remove(id)">
                                    {{ $t('videos.btn.delete' )}}
                                </v-btn>
                                <v-btn color="warning" outlined>
                                    {{ $t('videos.btn.edit' )}}
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                    <v-pagination :length="pagination.last_page"
                                  v-model="pagination.page"
                                  :total-visible="7"
                                  circle></v-pagination>
                </v-row>
            </v-card-text>


        </v-card>
    </div>
</template>

<script>
  import vue2Dropzone from 'vue2-dropzone';
  import 'vue2-dropzone/dist/vue2Dropzone.min.css';
  import dropzone_mixin from '../../../mixins/dropzone_options';
  // dropzone_mixin.dictDefaultMessage = "Переместите видео в область загрузки";

  export default {
    name: "index",

    mixins: [dropzone_mixin],
    components: {
      vueDropzone: vue2Dropzone
    },

    data() {
      return {
        videos: [],

        is_search: false,

        video: {
          title: '',
          url: ''
        },

        form: {
          valid: false,
          title: {
            rules: [
                v => v.length > 0 || this.$t('videos.form.rules.title.required'),
                v => v.length <= 255 || this.$t('videos.form.rules.title.length', { length: 255})
            ]
          },
        },

        search: {
          keyword: ''
        },

        pagination: {
          last_page: 1,
          page: 1
        },

        modal: false
      }
    },

    methods: {
      async loadData() {
        const response = await axios.post('/admin/videos', {
          params: {
            page: this.page
          }
        });

        if (response.data.status === 'error') {
          this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
          return false;
        }

        this.videos = response.data.videos.data;
        this.pagination.last_page = response.data.videos.last_page;
      },

      async remove(id) {
        const response = await axios.post(`/admin/news/delete/${id}`);

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
        const response = await axios.get('/admin/news/search', {
          params: {
            page: this.pagination.page,
            keyword: this.search.keyword
          }
        });

        if (response.data.status === 'error') {
          this.$swal(this.$t('swal,title.error'), response.data.msg, 'error');
          return false;
        }

        this.videos = response.data.videos.data;
        this.pagination.last_page = response.data.videos.last_page;

      },

      save() {
        this.modal = false;
        this.$refs.form.reset();
      },

      cancel() {
        this.modal = false;
        this.$refs.form.reset();
      }
    },

    created() {
      this.loadData();
      this.dropzone_options.dictDefaultMessage = this.$t('dropzone.dictDefaultMessage.videos');
    }
  }
</script>

<style scoped>

</style>
