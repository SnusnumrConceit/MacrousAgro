<template>
    <v-slide-group >
        <v-slide-item v-for="article in articles" :key="article.id">
            <v-card
                    class="mx-3"
                    max-width="400"
                    style="cursor: pointer"
                    @click="$router.push(`/articles/${article.id}`)"
            >
                <v-img :src="article.src || 'https://cdn.vuetifyjs.com/images/cards/docks.jpg'"
                       class="white--text align-end"
                       height="200px"
                >
                    <v-card-title>
                        {{ article.title }}
                    </v-card-title>
                </v-img>
                <v-card-text class="text--primary">
                    {{ article.description.substring(0, 60) }}
                </v-card-text>
            </v-card>
        </v-slide-item>
    </v-slide-group>
</template>

<script>
  export default {
    name: "ArticleList",

    props: {
      'articles': Array
    }
  }
</script>