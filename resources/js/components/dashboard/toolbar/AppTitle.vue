<template>
    <v-toolbar-title @click="$router.push('/')" style="cursor: pointer">
        <h2 class="display-1">
            {{ $t('app_name')}}
        </h2>
        <h6>
            {{ $t('app_subtitle')}}
        </h6>
    </v-toolbar-title>
</template>

<script>
  export default {
    name: "AppTitle"
  }
</script>