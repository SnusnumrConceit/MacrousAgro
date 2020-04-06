<template>
    <div>
        <v-card v-show="! loading">
            <v-form ref="form" v-model="form.valid">
                <v-card-title>
                    {{ video.title }}
                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col>
                            <video :src="video.src" controls></video>
                        </v-col>
                        <v-col>
                            <v-text-field v-model="video.title"
                                          :label="$t('videos.form.labels.title')"
                                          clearable
                                          counter
                                          :rules="form.title.rules"
                                          maxlength="100">
                            </v-text-field>
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
                        {{ $t('videos.btn.delete')}}
                    </v-btn>
                    <v-btn color="default" outlined @click="goBack">
                        {{ $t('videos.btn.back')}}
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>

        <v-skeleton-loader type="card" v-show="loading"></v-skeleton-loader>
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
        },

        form: {
          valid: false,
          title: {
            rules: [
              v => v !== '' || this.$t('videos.form.rules.title.required'),
              v => (v !== undefined && v !== null && v.length <= 100) || this.$t('videos.form.rules.title.length', { length: 255})
            ]
          },
        },

        loading: false
      }
    },

    computed: {
      id() {
        return this.$route.params.id;
      }
    },

    methods: {
      /**
       * Получение видео
       *
       */
      async loadData() {
        const response = await axios.get(`${this.$attrs.apiRoute}/videos/${this.id}/edit`);

        if (response.data.status === 'error') {
          this.$swal(this.$t('swal.title.error', response.data.msg, 'error'));
          return false;
        }

        this.video = response.data.video;
      },

      /**
       * Удаление видео
       *
       */
      async remove() {
        const response = await axios.delete(`${this.$attrs.apiRoute}/videos/${this.id}`);

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
       * Сохранение видео
       *
       */
      async save() {
        this.modal = false;

        const response = await axios.put(`${this.$attrs.apiRoute}/videos/${this.id}`, this.video);

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

      /**
       * Назад
       *
       */
      goBack() {
        this.$router.go(-1);
      },

      /**
       * Lazy-загрузка видео
       *
       * @returns {Promise<void>}
       */
      async initData() {
        this.loading = true;

        await this.loadData();

        this.loading = false;
      }
    },

    created() {
      if (this.id !== undefined) {
        this.initData();
      }
    }
  }
</script>

<style scoped>

</style>
