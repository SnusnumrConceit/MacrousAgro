<template>
    <div>
        <v-card>
            <v-toolbar>
                <v-toolbar-title>
                    {{ $t('photos.header')}}
                </v-toolbar-title>

                <v-divider class="mx-4" vertical inset></v-divider>
                <v-spacer></v-spacer>

                <v-text-field v-model="search.keyword"
                              append-icon="search"
                              label="Поиск"
                              single-line></v-text-field>

                <v-dialog v-model="modal" max-width="500px">
                    <template v-slot:activator="{on}">
                        <v-btn color="success" outlined v-on="on">
                            <i class="pe-7s-plus"></i>
                            {{ $t('photos.btn.add')}}
                        </v-btn>
                    </template>

                    <v-card>
                        <v-card-title>
                            {{ $t('photos.form.header')}}
                        </v-card-title>
                        <v-card-text>
                            <v-text-field v-model="photo.title"
                                          :label="$t('photos.form.labels.title')"
                                          clearable
                                          counter
                                          maxlength="100">

                            </v-text-field>

                            <vue-dropzone ref="dropzone"
                                          id="dropzone"
                                          :options="dropzone_options"></vue-dropzone>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>

                            <v-btn color="success" :disabled="! photo.url.length" @click="save()">
                                {{ $t('photos.btn.save')}}
                            </v-btn>
                            <v-btn color="blue darken-1" text @click="cancel()">
                                {{ $t('photos.btn.cancel')}}
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-toolbar>

            <v-card-text>
                <v-row>
                    <v-col cols="4" v-for="(photo, index) in photos" :key="photo.id">
                        <v-card>
                            <v-card-title>
                                {{ photo.title }}
                            </v-card-title>
                            <v-card-text>
                                <img :src="photo.url" alt="">
                            </v-card-text>
                            <v-card-actions>
                                <v-btn color="danger" outlined @click="remove(photo.id)">
                                    {{ $t('photos.btn.delete')}}
                                </v-btn>
                                <v-btn color="warning" outlined @click="edit(photo.id)">
                                    {{ $t('photos.btn.edit')}}
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                    <v-pagination :length="pagination.last_page"
                                  circle
                                  v-model="pagination.page"
                                  :total-visible="7"></v-pagination>
                </v-row>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
  import vue2Dropzone from 'vue2-dropzone';
  import 'vue2-dropzone/dist/vue2Dropzone.min.css';
  import dropzone_options from "../../../mixins/dropzone_options";

  export default {
    name: "index",

    mixins: [dropzone_options],

    components: {
      vueDropzone: vue2Dropzone
    },

    data() {
      return {
        photos: [],

        is_search: false,

        search: {
          keyword: ''
        },

        photo: {
          title: '',
          url: ''
        },

        pagination: {
          page: 1,
          last_page: 1
        },

        modal: false
      }
    },

    methods: {
      async loadData() {
        const response = await axios.post('/admin/photos', {
          params: {
            page: this.pagination.page
          }
        });

        if (response.data.status === 'error') {
          this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
          return false;
        }

        this.photos = response.data.photos.data;
        this.pagination.last_page = response.data.photos.last_page;
      },

      async searchData() {
        const response = await axios.get('/admin/photos/search', {
          params: {
            page: this.pagination.page,
            keyword: this.search.keyword
          }
        });

        if (response.data.status === 'error') {
          this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
          return false;
        }

        this.photos = response.data.photos.data;
        this.pagination.last_page = response.data.photos.last_page;
      },

      async remove(id) {
        const response = await axios.post(`/admin/photos/delete/${id}`);

        switch (response.data.status) {
          case 'error':
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            return false;

          case 'success':
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            this.loadData();
        }
      }
    },

    created() {
      this.loadData();
    }
  }
</script>

<style scoped>

</style>
