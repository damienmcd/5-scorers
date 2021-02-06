<template>
  <div class="loader bg-white">
    <div class="loader__content">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        version="1.0"
        width="50"
        height="50"
        viewBox="0 0 128 128"
        class="mb-4"
      >
        <g><path d="M56.9-.1h15.17V37H56.9V-.1z" fill="#0f766e"/><path d="M89.901 4.938l13.138 7.585-18.55 32.13-13.138-7.586 18.55-32.13z" fill="#e7f1f0" fill-opacity=".1"/><path d="M115.962 25.801l7.585 13.138-32.13 18.55-7.584-13.138 32.13-18.55z" fill="#e7f1f0" fill-opacity=".1"/><path d="M128.1 56.9v15.17H91V56.9h37.1z" fill="#e7f1f0" fill-opacity=".1"/><path d="M123.062 89.901l-7.585 13.138-32.13-18.55 7.586-13.138 32.13 18.55z" fill="#d1e5e3" fill-opacity=".19"/><path d="M102.199 115.962l-13.138 7.585-18.55-32.13 13.138-7.584 18.55 32.13z" fill="#bbd8d6" fill-opacity=".28"/><path d="M71.1 128.1H55.93V91H71.1v37.1z" fill="#a5cbc8" fill-opacity=".38"/><path d="M38.099 123.062l-13.138-7.585 18.55-32.13 13.138 7.586-18.55 32.13z" fill="#90c0bc" fill-opacity=".46"/><path d="M12.038 102.199L4.453 89.06l32.13-18.55 7.584 13.138-32.13 18.55z" fill="#7ab3af" fill-opacity=".55"/><path d="M-.1 71.1V55.93H37V71.1H-.1z" fill="#66a7a2" fill-opacity=".64"/><path d="M4.938 38.099l7.585-13.138 32.13 18.55-7.586 13.138-32.13-18.55z" fill="#4f9b95" fill-opacity=".73"/><path d="M25.801 12.038L38.94 4.453l18.55 32.13-13.138 7.584-18.55-32.13z" fill="#3a8f88" fill-opacity=".82"/><animateTransform attributeName="transform" type="rotate" values="330 64 64;300 64 64;270 64 64;240 64 64;210 64 64;180 64 64;150 64 64;120 64 64;90 64 64;60 64 64;30 64 64;0 64 64" calcMode="discrete" dur="1080ms" repeatCount="indefinite"/></g>
      </svg>
      <div class="loader__line-1 font-sans text-lg text-center uppercase antialiased font-semibold mb-2">One Moment</div>
      <div class="loader__line-2 font-sans text-md text-center antialiased font-light">Loading Game Data...</div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Loader'
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
.loader {
  position: absolute;
  left: 0;
  top: 120px;
  display: flex;
  flex-flow: row wrap;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: calc(100vh - 120px);
  z-index: 10;

  &__content {
    display: flex;
    flex-flow: row wrap;
    align-items: center;
    justify-content: center;
  }

  &__line-1 {
    flex: 1 0 100%;
  }

  &__line-2 {
    flex: 1 0 100%;
  }
}

@media screen and (max-width: 768px) {
  .loader {
    top: 168px;
    height: calc(100vh - 168px);
  }
}

@media screen and (max-width: 375px) {
  .loader {
    top: 384px;
    height: calc(100vh - 384px);
  }
}
</style>
