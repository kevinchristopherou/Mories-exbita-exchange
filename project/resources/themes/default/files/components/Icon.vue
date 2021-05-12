<template>
  <i class="fa" :class="{
    ['fa-' + scale]: scale !== '',
    ['fa-' + name]: name !== '',
    ['fa-rotate-' + rotate]: rotate !== '',
    'fa-spin': spin,
    'fa-pulse': pulse,
    'fa-fw': fw
  }"
    v-on:click="$emit('click',$event)">
  </i>
</template>

<script>
  export default {
    name: 'icon',

    props: {
      name: {
        type: String,
        default: ''
      },
      scale: {
        type: String,
        default: ''
      },
      rotate: {
        type: String,
        default: ''
      },
      spin: {
        type: Boolean,
        default: false
      },
      pulse: {
        type: Boolean,
        default: false
      },
      fw: { //fixed width
        type: Boolean,
        default: false
      },
    }
  }
</script>
