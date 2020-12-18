<template>
  <div class="flex items-center justify-center flex-wrap bg-grey-100 p-6">
    <h1 class="font-sans text-lg antialiased font-light mb-6">5 Scorers</h1>

    <div class="form-wrapper container">
      <form class="form-signin" method="POST">
        <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">Email</span>
            <input type="text" name="email" class="form-control" placeholder="Email Address" required>
        </div>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <button class="button button--red button--icon login" type="submit">Login</button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Login'
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
</style>
