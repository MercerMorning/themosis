<template>
  <div class="chat-container">
    <form v-on:submit.prevent="addThread" method="post" id="add-thread-form" action="chat/create_thread">
      <input type="text" name="name" id="add-thread-text" required>
      <input type="submit">
    </form>


    <div style="width: 50%">
        <ul>
          <li v-for="thread in JSON.parse(threads)">
            <button data-threadid={{ thread.id }} v-on:click="">{{ thread.subject }}</button>
          </li>
        </ul>
    </div>
    <div style="width: 50%; float: right">
        Здесь пока пусто
    </div>
    <ul style="width: 70%">
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
    // console.log(this.threads());
  },
  props: {
    threads: String,
  },
  data: function () {
    {
      return {
        messages: ['hi!', 'df'],
        threadBody: null,
      }
    }
  },
  methods: {
    getThreads: function () {
      return fetch('/')
    },
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
      this.threads = axios.post('chat/create_thread/',  {
        'name' : new FormData(event.target).get('name')
      })
    },
    openThread: function (event) {
      console.log(123);
    }
  }
}


</script>
