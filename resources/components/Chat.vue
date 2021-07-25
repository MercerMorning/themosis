<template>
  <div class="chat-container">
    <form v-on:submit.prevent="addThread" method="post" id="add-thread-form" action="chat/create_thread">
      <input type="text" name="name" id="add-thread-text" required>
      <input type="submit">
    </form>


    <div style="width: 50%">
        <ul>
          <li v-for="thread in JSON.parse(threadsData)">
            <button v-bind:data-id="thread.id" v-on:click="openThread">{{ thread.subject }}</button>
          </li>
        </ul>
    </div>

    <div style="width: 50%; float: right">
      <div v-if="threadMessages">

      </div>
      <div v-else>
        Здесь пока пусто
      </div>
    </div>
    <ul style="width: 70%">
      <li v-for="threadMessage in threadMessages">
        {{ threadMessage.body }}
      </li>
    </ul>
    <form action="" v-on:submit.prevent="sendMessage">
      <input type="hidden" name="threadId" v-bind:value="currentThread">
      <input type="text" name="body" id="message-input">
      <input type="submit">
    </form>
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
        threadsData: this.threads,
        threadBody: null,
        currentThread: null,
        threadMessages: null,
      }
    }
  },
  methods: {
    getThreads: function () {
      return fetch('/')
    },
    sendMessage: function () {
      event.preventDefault();
      axios.post('chat/send_message_to_thread/',  {
        'body' : new FormData(event.target).get('body'),
        'thread_id' : new FormData(event.target).get('threadId')
      }).then(response => {
        this.threadMessages = response.data
      })
    },
    addThread: function (event) {
      event.preventDefault();
      axios.post('chat/create_thread/',  {
        'name' : new FormData(event.target).get('name')
      }).then( response => {
        this.threadsData = response.data
      })
    },
    openThread: function (event) {
      axios.get('chat/get_thread?thread_id=' +  event.target.dataset.id).then( response => {
        console.log(response.data)
        this.threadMessages = response.data
        this.currentThread = event.target.dataset.id;
      })
    }
  }
}


</script>
