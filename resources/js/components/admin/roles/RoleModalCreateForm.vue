<template>
    <v-dialog v-model="show" max-width="1000px" persistent>
        <template v-slot:activator="{on}">
            <v-btn color="success" outlined v-on="on" dark @click="on">
                <i class="pe-7s-plus"></i> {{ $t('buttons.add') }}
            </v-btn>
        </template>

        <role-form @hide-modal="hide"></role-form>
    </v-dialog>
</template>

<script>
  import RoleForm from './RoleForm';

  export default {
    name: "RoleModalCreateForm",

    components: {
      RoleForm
    },

    data() {
      return {
        show: false
      }
    },

    methods: {
      hide() {
        this.show = false;
      }
    }
  }
</script>

<style scoped>

</style>