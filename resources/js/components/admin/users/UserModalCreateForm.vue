<template>
    <v-dialog v-model="show" max-width="700px" max-height="1000px">
        <template v-slot:activator="{on}">
            <v-btn outlined color="success" class="" dark v-on="on">
                <i class="pe-7s-plus"></i> {{ $t('buttons.add')}}
            </v-btn>
        </template>

        <user-form @hide-modal="hide"></user-form>
    </v-dialog>
</template>

<script>
  import UserForm from './UserForm';

  export default {
    name: "UserModalCreateForm",

    components: {
      UserForm
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