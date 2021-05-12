<template>

  <input class="form-control"
         ref="input"
         :placeholder="placeholder"
         v-bind:value="value"
         v-on:input="updateValue($event)"
         v-on:focus="onFocus($event)"
         v-on:blur="formatValue"
         v-on:keydown="keyDown($event)"
         v-on:paste="formatValue"
  >
</template>

<script>
  export default {
    name: 'money-input',
    props: {
      value: {
        default: 0
      },
      placeholder: {
        type: String,
        default: ''
      },
      decimals: {
        type: Number,
        default: 2
      }
    },
    mounted: function () {
      this.checkEmpty();
      //this.formatValue();
    },
    methods: {
      checkEmpty: function () {
        if (this.value === 0) {
          this.$refs.input.value = '';
        }
      },
      onFocus: function (event) {
        if (this.value === 0) {
          return;
        }

        setTimeout(function () {
          event.target.select()
        }, 0)
      },
      updateValue: function (event) {
        let value = event.target.value;
        this.$emit('input', value)
      },
      formatValue: function () {
        this.$refs.input.value = this.formatDecimal(this.value, this.decimals);
        this.$emit('input', this.$refs.input.value)
        this.$emit('change', this.$refs.input.value)
      },
      keyDown(e) {


        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
          // Allow: Ctrl/cmd+A
          (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
          // Allow: Ctrl/cmd+C
          (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
          // Allow: Ctrl/cmd+X
          (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
          // Allow: Ctrl/cmd+R
          (e.keyCode == 82 && (e.ctrlKey === true || e.metaKey === true)) ||
          // Allow: home, end, left, right
          (e.keyCode >= 35 && e.keyCode <= 39)) {
          // let it happen, don't do anything
          return;
        }

        //prevent if new string does not match the regex
        let pos = e.target.selectionStart;
        let str = this.$refs.input.value;
        let output = [str.slice(0, pos), e.key, str.slice(pos)].join('');
        let reg = RegExp('^\\d*(\\.\\d{0,' + this.decimals + '})?$');
        if (!reg.test(output)) {
          e.preventDefault();
        }
      }
    }
  }
</script>
