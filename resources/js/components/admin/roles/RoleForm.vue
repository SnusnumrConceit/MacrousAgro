<template>
    <v-card>
        <v-form v-model="form.valid" ref="form" v-show="! loading">
            <v-card-title class="headline">
                {{ $t('roles.form.header') }}
            </v-card-title>

            <v-card-text>
                <errors />

                <v-text-field v-model="role.name"
                              :label="$t('roles.form.labels.name')"
                              required
                              counter
                              clearable
                              maxlength="255"
                              :rules="form.name.rules">
                </v-text-field>

                <v-text-field v-model="role.slug"
                              :label="$t('roles.form.labels.slug')"
                              required
                              counter
                              clearable
                              maxlength="255"
                              :rules="form.slug.rules">
                </v-text-field>

                <v-textarea v-model="role.description"
                            :label="$t('roles.form.labels.description')"
                            required
                            counter
                            maxlength="255"
                            :rules="form.description.rules">
                </v-textarea>

                <v-row>
                    <v-col cols="4" v-for="(parent, key, index) in permissions" :key="index" class="d-flex flex-sm-column" v-if="key">
                        <v-card>
                            <v-card-text v-if="parent">
                                <v-card-title class="text-capitalize">
                                    {{ headers[index] }}
                                </v-card-title>
                                <v-checkbox v-model="role.permissions"
                                            v-for="permission in parent"
                                            :value="permission.id"
                                            :key="permission.id"
                                            :label="permission.description">
                                </v-checkbox>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-card-text>

            <v-card-actions>
                <v-spacer />

                <v-btn color="success"
                       :disabled="! form.valid || ! role.permissions.length"
                       @click="id ? update() : create()">
                    {{ $t('buttons.save')}}
                </v-btn>
                <v-btn color="blue darken-1"
                       text
                       @click="id ? $router.back() : cancel()">
                    {{ $t('buttons.cancel') }}
                </v-btn>
            </v-card-actions>
        </v-form>

        <v-skeleton-loader v-show="loading" type="card" />
    </v-card>
</template>

<script>
  import {mapActions} from 'vuex';

  export default {
    name: "RoleForm",

    data() {
      return {
        role: {
          name: '',
          description: '',
          permissions: [],

          headers: []
        },

        form: {
          valid: false,
          name: {
            rules: [
              v => v !== '' || this.$t('roles.form.rules.name.required'),
              v => (v !== undefined && v !== null && v.length <= 255) || this.$t('roles.form.rules.name.length', {length: 255})
            ],
          },

          slug: {
            rules: [
              v => v !== '' || this.$t('roles.form.rules.slug.required'),
              v => (v !== undefined && v !== null && v.length <= 255) || this.$t('roles.form.rules.slug.length', {length: 255})
            ],
          },

          description: {
            rules: [
              v => v !== '' || this.$t('roles.form.rules.description.required'),
              v => (v !== undefined && v !== null && v.length <= 255) || this.$t('roles.form.rules.description.length', {length: 255})
            ]
          },
        },

        loading: false,

        permissions: []
      }
    },

    computed: {
      id() {
        return this.$route.params.id;
      },
    },

    methods: {

      ...mapActions('errors', [
        'setErrors',
        'resetErrors'
      ]),

      ...mapActions('notifications', [
        'showNotification'
      ]),

      getParent(index) {
        console.log(index);
      },

      async getRole() {
        try {
          const response = await axios.get(`/roles/${this.id}`);

          this.role = response.data.role;
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.errors || e.message });
        }
      },

      /**
       * Получить права
       *
       * Get list of the permissions
       *
       * @returns {Promise<void>}
       */
      async getPermissions() {
        try {
          const response = await axios.get(`/permissions`);

          this.permissions = response.data.permissions;
          this.headers = response.data.headers;
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.errors || e.message });
        }
      },

      /**
       * Создание роли с соответствующими правами
       *
       * Save role with additional permissions
       *
       * @returns {Promise<void>}
       */
      async create() {
        try {
          const response = await axios.post(`/roles`, this.role);

          this.showNotification({ type: 'success', message:  response.data.message});

          this.show = false;

          this.$root.$emit('role-created', this.role);

          if (! this.id) {
            this.$emit('hide-modal');
          }

          this.resetForm();
        } catch (e) {
          this.setErrors(e.response.data.errors || e.message);
        }
      },

      /**
       * Создание роли с соответствующими правами
       *
       * Save role with additional permissions
       *
       * @returns {Promise<void>}
       */
      async update() {
        try {
          const response = await axios.patch(`/roles/${this.id}`, this.role);

          this.showNotification({ type: 'success', message:  response.data.message});

          this.$router.back();

          this.resetForm();
        } catch (e) {
          this.setErrors(e.response.data.errors || e.message);
        }
      },

      /**
       * Поочерёдный вызов функций
       *
       * Queue function execution
       *
       * @returns {Promise<void>}
       */
      async initData() {
        this.loading = true;

        await this.getPermissions();

        if (this.id) {

          this.getRole();
        }

        this.loading = false;
      },

      resetForm() {
        this.$refs.form.reset();
        this.resetErrors();
      },

      cancel() {
        this.resetForm();

        if (! this.id) {
          this.$emit('hide-modal');
        }
      },
    },

    created() {
      this.initData();
    }
  }
</script>