<template>
    <div>
        <v-card>
            <category-toolbar></category-toolbar>

            <v-card-text>
                <category-list></category-list>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
  import CategoryList from './CategoryList';
  import CategoryToolbar from './CategoryToolbar';

  export default {
    name: "index",

    components: {
      CategoryList,
      CategoryToolbar
    }
  }
</script>