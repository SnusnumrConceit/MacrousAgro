<template>
    <div>
        <v-simple-table :height="750" v-show="! loading && roles.length" ref="table">
            <template v-slot:default>
                <thead>
                <th v-for="header in table.headers" :key="header" class="text-center">
                    {{ header }}
                </th>
                </thead>
                <tbody>
                <tr v-for="role in roles" :key="role.id">
                    <td>
                        {{ role.name }}
                    </td>
                    <td>
                        {{ role.description.substring(0, 120) }}
                    </td>
                    <td>
                        <v-tooltip top color="primary">
                            <template v-slot:activator="{ on }">
                                <v-icon color=""
                                        v-on="on"
                                        small
                                        @click="$router.push(`/admin/roles/${role.slug}`)">
                                    mdi-pencil
                                </v-icon>
                            </template>
                            <span>
                            {{ $t('tooltips.edit')}}
                        </span>
                        </v-tooltip>

                        <v-tooltip top color="error">
                            <template v-slot:activator="{ on }">
                                <v-icon color="red"
                                        v-on="on"
                                        small
                                        @click="remove(role.slug)">
                                    mdi-delete
                                </v-icon>
                            </template>
                            <span>
                            {{ $t('tooltips.delete') }}
                        </span>
                        </v-tooltip>
                    </td>
                </tr>
                </tbody>
            </template>
        </v-simple-table>

        <span v-show="! loading" class="d-flex flex-row-reverse">
            {{ $t('total', {total: total}) }}
        </span>

        <v-skeleton-loader v-show="loading" type="table-row-divider@6"/>

        <v-alert color="info" outlined v-if="! loading && ! roles.length">
            <div class="">
            <span v-show="! searching">
                {{ $t('roles.no_roles') }}
            </span>
                <span v-show="searching">
                {{ $t('no_results') }}
            </span>
            </div>
        </v-alert>
    </div>
</template>

<script>
  export default {
    name: "RoleList",

    props: {
      roles: {
        default: [],
        type: Array,
        required: true
      },

      loading: {
        default: false,
        type: Boolean,
        required: true
      },

      searching: {
        default: false,
        type: Boolean,
        required: false
      },

      total: {
        default: 0,
        type: Number,
        required: false
      }
    },

    data() {
      return {
        table: {
          headers: [
            this.$t('roles.table.headers.name'),
            this.$t('roles.table.headers.description'),
            ''
          ]
        }
      }
    },

    methods: {
      async remove(slug) {
        this.$emit('role-deleted', {slug : slug});
      }
    }
  }
</script>

<style scoped>

</style>