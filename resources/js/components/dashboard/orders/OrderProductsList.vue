<template>
    <tbody>
        <tr v-for="position in positions" :key="position.id" v-if="positions.length">
            <td class="text-left">{{ position.product.title }}</td>
            <td class="text-left">{{ position.product.price }} ₽</td>
            <td v-if="canEdit">
                <v-icon
                        small
                        @click="remove(position.product)"
                >
                    mdi-delete
                </v-icon>
            </td>
        </tr>
    </tbody>
</template>

<script>
  export default {
    name: "OrderProductsList",

    props: {
      positions: {
        default: [],
        required: true
      },

      canEdit: {
        default: false
      }
    },

    methods: {
      remove(product) {
        this.$emit('order-position-removed', {product: product});
      }
    },
  }
</script>

<style scoped>

</style>