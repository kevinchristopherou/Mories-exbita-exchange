<template src="./LaravelPagination.htm"></template>
<script>
  export default {
    name: 'laravel-pagination',
    props: {
      dataset: {
        type: Object,
        default: null
      },
    },
    methods: {
      onInput: function (page) {
        this.$emit('change', {page: page})
      },
    }
  }
</script>
