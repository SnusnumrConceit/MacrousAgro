<template>
    <div>
        <v-card>
            <v-card-title>
                {{ video.title }}
            </v-card-title>
            <v-card-text>
                <v-row>
                    <v-col>
                        <video :src="video.path" controls></video>
                    </v-col>
                    <v-col>
                        <v-text-field v-model="video.title"
                                      :label="$t('videos.form.labels.title')"
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
                    {{ $t('videos.btn.delete')}}
                </v-btn>
                <v-btn color="default" outlined @click="goBack">
                    {{ $t('videos.btn.back')}}
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
        video: {
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
        const response = await axios.get(`/admin/videos/${this.id}/edit`);

        if (response.data.status === 'error') {
          this.$swal(this.$t('swal.title.error', response.data.msg, 'error'));
          return false;
        }

        this.video = response.data.video;
      },

      async remove() {
        const response = await axios.delete(`/admin/videos/${this.id}`);

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

        const response = await axios.put(`/admin/videos/${this.id}`, this.video);

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
      if (this.id !== undefined) {
        this.loadData();
      }
    }
  }
</script>

<style scoped>

</style>
