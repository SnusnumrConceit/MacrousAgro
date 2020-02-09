<template>
    <div>
        <v-card>
            <v-card-title>
                {{ photo.title }}
            </v-card-title>
            <v-card-text>
                <v-row>
                    <v-col>
                        <v-img :src="photo.path" alt=""></v-img>
                    </v-col>
                    <v-col>
                        <v-text-field v-model="photo.title"
                                      :label="$t('photos.form.labels.title')"
                                      clearable
                                      counter
                                      maxlength="100">
                        </v-text-field>
                        <v-btn color="success" outlined @click="save">
                                Изменить
                        </v-btn>
                    </v-col>
                </v-row>
            </v-card-text>
            <v-card-actions>
                <v-btn color="error" outlined @click="remove">
                    {{ $t('photos.btn.delete')}}
                </v-btn>
                <v-btn color="default" outlined @click="goBack">
                    {{ $t('photos.btn.back')}}
                </v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>

<script>
  export default {
    name: "form",

    data() {
      return {
        photo: {
          title: '',
          path: ''
        }
      }
    },

    computed: {
      id() {
        return this.$route.params.id;
      }
    },

    methods: {
      async loadData() {
        const response = await axios.get(`/admin/photos/${this.id}/edit`);

        if (response.data.status === 'error' || response.status !== 200) {
          this.$swal(this.$t('swal.title.error', response.data.msg, 'error'));
          return false;
        }

        this.photo = response.data.photo;
      },

      async remove() {
        const response = await axios.delete(`/admin/photos/${this.id}`);

        switch (response.data.status) {
          case 'error':
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            return false;

          case 'success':
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            this.loadData();
        }
      },

      async save() {
        this.modal = false;

        const response = await axios.put(`/admin/photos/${this.id}`, this.photo);

        switch (response.status) {
          case 200:
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            this.goBack();
            break;

          case 500:
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            break;
        }
      },

      goBack() {
        this.$router.go(-1);
      }
    },

    created() {
      if (this.id) {
        this.loadData();
      }
    }
  }
</script>

<style scoped>
    .img-container {
        max-height: 600px;
        max-width: 850px;
    }
</style>
