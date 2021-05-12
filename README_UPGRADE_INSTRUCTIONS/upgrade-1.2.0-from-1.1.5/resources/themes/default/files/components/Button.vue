<template src="./Button.htm"></template>

<script>
export default {
  name: 'v-button',

  props: {
    type: {
      type: String,
      default: 'primary'
    },

    nativeType: {
      type: String,
      default: 'submit'
    },

    loading: {
      type: Boolean,
      default: false
    },

    block: Boolean,
    big: Boolean,
  }
}
</script>