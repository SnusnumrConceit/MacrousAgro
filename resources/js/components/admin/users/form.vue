<template>
    <div>
        <v-card v-show="! loading">
            <v-form v-model="form.valid" ref="user_form">
                <v-card-title>
                    Редактирование пользователя
                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col cols="12" sm="12" md="4">
                            <v-text-field v-model="user.email"
                                          :label="$t('users.form.labels.email')"
                                          required
                                          counter
                                          clearable
                                          maxlength="255"
                                          :rules="form.email.rules"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="12" md="4">
                            <v-text-field v-model="user.last_name"
                                          :label="$t('users.form.labels.last_name')"
                                          required
                                          counter
                                          clearable
                                          maxlength="100"
                                          minlength="2"
                                          :rules="form.last_name.rules"></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="6" md="4" lg="4">
                            <v-text-field v-model="user.first_name"
                                          :label="$t('users.form.labels.first_name')"
                                          required
                                          counter
                                          clearable
                                          maxlength="60"
                                          minlength="2"
                                          :rules="form.first_name.rules">
                            </v-text-field>
                        </v-col>
                        <v-col cols="12" sm="12" md="12">
                            <v-text-field v-model="user.birthday"
                                          @click="form_calendar = true"
                                          :label="$t('users.form.labels.birthday')"
                                          prepend-icon="event"
                                          :rules="form.birthday.rules"
                                          readonly
                            ></v-text-field>

                            <v-date-picker v-model="user.birthday"
                                           :style="{position: 'absolute', right: '20%', 'z-index': 3}"
                                           no-title
                                           scrollable
                                           v-if="form_calendar"
                                           :locale="$i18n.locale">
                                <v-spacer></v-spacer>
                                <v-btn flat color="blue darken-1" @click="form_calendar = false" text>
                                    {{ $t('users.btn.cancel') }}
                                </v-btn>
                                <v-btn flat color="primary" outlined @click="form_calendar = false">OK</v-btn>
                            </v-date-picker>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="success" outlined @click="save" :disabled="! form.valid">
                        {{ $t('users.btn.edit')}}
                    </v-btn>
                    <v-btn @click="goBack" color="default" outlined>
                        {{ $t('users.btn.back')}}
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>

        <v-skeleton-loader type="table-row-divider@4" v-show="loading"></v-skeleton-loader>
        <!--<v-btn outlined color="error" @click="remove">-->
            <!--{{ $t('users.btn.delete') }}-->
        <!--</v-btn>-->
    </div>
</template>

<script>
  export default {
    name: "form",

    data() {
      return {
        user: {
          email: '',
          last_name: '',
          first_name: '',
          birthday: ''
        },

        form: {
          valid:false,
          email: {
            rules: [
              v => v !== '' || this.$t('users.form.rules.email.required'),
              v => (v !== undefined && v !== null && v.length > 10) || this.$t('users.form.rules.email.min_length', {length:10}),
              v => (v !== undefined && v !== null && v.length <= 255) || this.$t('users.form.rules.email.max_length', {length: 255})
            ]
          },

          birthday: {
            rules: [
              v => v !== '' || this.$t('users.form.rules.birthday.required'),
              v => (v !== undefined && v !== null && v < Date.now) || this.$t('users.form.rules.birthday.max_length'),
            ]
          },

          first_name: {
            rules: [
              v => v !== '' || this.$t('users.form.rules.first_name.required'),
              v => (v !== undefined && v !== null && v.length >= 2) || this.$t('users.form.rules.first_name.min_length', {length:2}),
              v => (v !== undefined && v !== null && v.length <= 60) || this.$t('users.form.rules.first_name.max_length', {length: 60})
            ]
          },

          last_name: {
            rules: [
              v => v !== '' || this.$t('users.form.rules.last_name.required'),
              v => (v !== undefined && v !== null && v.length >= 2) || this.$t('users.form.rules.last_name.min_length', {length:2}),
              v => (v !== undefined && v !== null && v.length <= 100) || this.$t('users.form.rules.last_name.max_length', {length: 100})
            ]
          },

          password: {
            rules: [
              v => v !== '' || this.$t('users.form.rules.password.required'),
              v => (v !== undefined && v !== null && v.length >= 8) || this.$t('users.form.rules.password.min_length', {length:8}),
              v => (v !== undefined && v !== null && v.length <= 60) || this.$t('users.form.rules.password.max_length', {length: 255})
            ]
          },

          password_confirmation: {
            rules: [
              v => v !== '' || this.$t('users.form.rules.password_confirmation.required'),
              v => v === this.user.password || this.$t('users.form.rules.password_confirmation.not_equals')
            ]
          }
        },
        form_calendar: false,

        show: {
          password: false,
          password_confirmation: false
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
      async loadData() {
        this.loading = true;
        const response = await axios.get(`${this.$attrs.apiRoute}/users/${this.id}/edit`);

        if (response.data.status === 'error' || response.status !== 200) {
          this.$swal(this.$t('swal.title.error', response.data.msg, 'error'));
          this.loading = false;
          return false;
        }

        this.user = response.data.user;
        this.loading = false;
      },

      async save() {

        const response = await axios.put(`${this.$attrs.apiRoute}/users/${this.id}`, {
          ...this.user
        });

        switch (response.data.status) {
          case 'error':
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            return false;

          case 'success':
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            this.goBack();
            break;
        }
      },

      async remove() {
        const response = await axios.delete(`${this.$attrs.apiRoute}/users/${this.id}`);

        switch (response.data.status) {
          case 'error':
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            return false;

          case 'success':
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            this.goBack();
            break;
        }
      },

      goBack() {
        this.$router.go(-1);
      },

      async initData() {
        await this.loadData();
      }
    },

    created() {
      if (this.id) {
        this.initData();
      }
    }
  }
</script>

<style scoped>

</style>
