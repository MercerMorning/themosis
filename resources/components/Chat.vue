<template>
  <div class="chat-container">
    <form method="post" id="add-thread-form" action="chat/create_thread">
      <input type="hidden" name="_token" v-bind:value="token">
      <input type="text" name="name" id="add-thread-text" required>
      <input type="submit">
    </form>

    <ul>
      <li v-for="message in messages">
        {{ message }}
      </li>
    </ul>
<!--    <form action="" v-on:submit.prevent="sendMeоssage">-->
<!--      <input type="hidden" name="__nonce" value="<?php echo wp_create_nonce('message') ;?> ">-->
<!--      <input type="text" id="message-input">-->
<!--      <input type="submit">-->
<!--    </form>-->
  </div>
</template>


<script>
export default {
  mounted: function(){
    // console.log(this.token)
    console.log("Hello Vue!");
  },
  props: {
    token: String,
  },
  data: function () {
    {
      return {
        messages: ['hi!', 'df'],
      }
    }
  },
  methods: {
    sendMessage: function () {
      event.preventDefault();
      console.log(1);
      // // `this` внутри методов указывает на экземпляр Vue
      // alert('Привет, ' + this.name + '!')
      // // `event` — нативное событие DOM
      // if (event) {
      //     alert(event.target.tagName)
      // }
    },
    addThread: function (event) {
      event.preventDefault();
    }
  }
}
</script>
