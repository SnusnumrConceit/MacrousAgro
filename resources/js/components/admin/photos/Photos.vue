<template>
    <div>
        <v-card>
            <photo-toolbar></photo-toolbar>

            <v-card-text>
                <photo-list></photo-list>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
  import PhotoList from './PhotoList';
  import PhotoToolbar from './PhotoToolbar';

  export default {
    name: "index",

    components: {
      PhotoList,
      PhotoToolbar
    }
  }
</script>