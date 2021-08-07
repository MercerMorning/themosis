<template>
  <div class="chat-container">
    <div class="menu-threads">
      <div class="menu-threads__current-user">
        <a class="menu-threads__link current-user">
          <img class="thread-link__participant_ava" v-bind:src="currentUserData.ava">
          <div class="thread-link__current-user_name">
            {{ currentUserData.first_name + ' ' +  currentUserData.last_name}}
          </div>
        </a>
      </div>
      <ul class="menu-threads__list">
        <li v-for="thread in threadsData"
            v-on:click="showThread"
            v-bind:data-id="thread.id"
            v-bind:class="[thread.id === currentThread
               ? 'menu-threads__item active'
               : 'menu-threads__item']">
          <a class="menu-threads__link">
<!--            <img class="thread-link__participant_ava" src="/content/themes/myinvision/assets/images/person.png">-->
            <div class="thread-link__dialog">
              <div class="thread-link__content">
                <span class="thread-participant_name">{{ thread.subject }}</span>
                <span class="thread-participant_message">{{ thread.lastMessage }}</span>
              </div>
              <div class="thread-link__date-time">
                <span>{{ thread.datetime }}</span>
              </div>
            </div>
          </a>
        </li>
      </ul>
    </div>
    <div class="chat mobile-hidden">
      <div class="chat__participant-chat">
        <svg width="20" height="18">
          <use xlink:href="/content/themes/myinvision/assets//images/previous.svg#previous"></use>
        </svg>
        <span>Phillip Torff</span>
        <img class="" src="/content/themes/myinvision/assets/images/person.png">
      </div>
      <div class="chat-body">

        <label v-for="(groupMessages, date) in threadMessages">
          <div class="chat-message__date">
                <span>
                    {{ date }}
                </span>
          </div>
          <div v-for="groupMessage in groupMessages"
               v-bind:class="[groupMessage.user_info.user_id === currentUserData.id
               ? 'chat-message__message chat-message__message_current-user'
               : 'chat-message__message chat-message__message_foreign-user']">
            <span v-if="groupMessage.user_info.user_id !== currentUserData.id" class="chat-message__autor">
              {{ groupMessage.user_info.first_name + ' ' + groupMessage.user_info.last_name }}
            </span>
            <div class="chat-message__message_message-content">
              <img class="thread-link__participant_ava"
                   v-bind:src="groupMessage.user_info.ava">
              <div class="chat-message__user-messages">
                <div v-for="message in groupMessage.messages" class="chat-message__message_message-body">
                  <div class="chat-message__text">
<!--                    <img class="message_image" v-if="message.is_file" v-bind:src="message.body">-->
                    <span>{{ message.body }}</span>
                  </div>
                  <div class="chat-message__time">{{ message.created_at }}</div>
                </div>
              </div>

            </div>
          </div>
        </label>
      </div>
      <form class="chat-footer">
        <div class="chat-footer__input-wrpa">
          <label class="chat-footer__input-file">
            <svg width="20" height="18">
              <use xlink:href="/content/themes/myinvision/assets//images/sprite.svg#icon-clip"></use>
            </svg>
            <input type="file" name="file">
          </label>
          <label class="chat-footer__input-text">
            <textarea class="input-message" type="text" name="text" placeholder="Введите сообщение"></textarea>
          </label>

          <label class="chat-footer__send-message">
            <button class="button chat-footer__button" type="submit">
                            <span class="send-button_text">
                                Отправить
                            </span>
            </button>
            <svg width="20" height="18">
              <use xlink:href="/content/themes/myinvision/assets//images/send.svg#send"></use>
            </svg>
          </label>
        </div>
      </form>
    </div>

  </div>
</template>


<script>
export default {
  mounted: function(){
    const socket = new WebSocket("ws://localhost:8999");

    socket.onopen = () => {
      socket.send("Hello!");
    };

    socket.onmessage = (data) => {
      console.log(data.data);
    };
  },
  props: {
    threads: String,
    currentuser: String,
  },
  data: function () {
    {
      return {
        threadsData: JSON.parse(this.threads),
        currentUserData: JSON.parse(this.currentuser),
        currentThread: this.currentThread ?? null,
        threadMessages: null
      }
    }
  },
  methods: {
    showThread : function (e) {
      let idItem = event.target.closest('.menu-threads__item');
      axios.get('/chat/get_thread?id=' +  idItem.dataset.id).then( response => {
        this.threadsData = response.data.threads
        this.currentThread = response.data.currentThread;
        this.threadMessages = response.data.threadMessages;
      })
    }

      //   getThreads: function () {
      //     return fetch('/')
      //   },
      //   sendMessage: function (event) {
      //
      //     let formFile = new FormData(event.target).get('file') ?? new FormData(event.target).get('file');
      //
      //     console.log(formFile.size)
      //     if (formFile.size !== 0) {
      //       var reader = new FileReader();
      //       reader.readAsDataURL(formFile);
      //       reader.onload = function () {
      //         axios.post('/chat/send_message_to_thread/', {
      //           'body': reader.result,
      //           'thread_id': new FormData(event.target).get('threadId'),
      //           // 'file' : 'sdf'
      //         }).then(response => {
      //           console.log(response.data)
      //           // this.threadMessages = response.data
      //         })
      //       }
      //     } else {
      //       axios.post('/chat/send_message_to_thread/',  {
      //         'body' : new FormData(event.target).get('body'),
      //         'thread_id' : new FormData(event.target).get('threadId'),
      //         // 'file' : 'sdf'
      //       }).then(response => {
      //         this.threadMessages = response.data
      //       })
      //     }
      //     document.querySelector('.chat-footer').reset()
      //
      //   },
      //   // addThread: function (event) {
      //   //   event.preventDefault();
      //   //   axios.post('/chat/create_thread/',  {
      //   //     'name' : new FormData(event.target).get('name')
      //   //   }).then( response => {
      //   //     this.threadsData = response.data
      //   //   })
      //   // },
      //   openThread: function (event) {
      //     document.querySelector('.chat-footer').reset()
      //     if (event.target.classList == 'menu-threads__link') {
      //       if (!event.target.dataset.id) {
      //         console.log(event.target.dataset.participantid)
      //         axios.post('/chat/create_thread/',  {
      //           'participant_id' : event.target.dataset.participantid
      //         }).then( response => {
      //           this.threadsData = response.data
      //           let newThreadId = response.data.new_thread_id
      //           axios.get('/chat/get_thread?thread_id=' +  newThreadId).then( response => {
      //             this.threadMessages = response.data
      //             this.currentThread = newThreadId;
      //             setCookie('currentThread',  JSON.stringify(newThreadId))
      //             setCookie('threadMessages',  JSON.stringify(response.data))
      //           })
      //         })
      //
      //       } else {
      //         axios.get('/chat/get_thread?thread_id=' +  event.target.dataset.id).then( response => {
      //           this.threadMessages = response.data
      //           this.currentThread = event.target.dataset.id;
      //           setCookie('currentThread',  JSON.stringify(event.target.dataset.id))
      //           setCookie('threadMessages',  JSON.stringify(response.data))
      //         })
      //       }
      //     }
      //
      //   },
      //   invervalRecievingThreadData: function () {
      //       setInterval(() => {
      //         axios.get('/chat/get_thread?thread_id=' +  JSON.parse(getCookie('currentThread'))).then( response => {
      //           this.threadMessages = response.data
      //           this.currentThread = JSON.parse(getCookie('currentThread'));
      //         }).catch(function (error) {
      //           console.log(error)
      //         })
      //         axios.get('/chat/get_threads').then( response => {
      //           this.threadsData = response.data
      //         }).catch(function (error) {
      //           console.log(error)
      //         })
      //       }, 3000)
      //
      //   },
      //   openCreateThemeModal()
      //   {
      //     this.createNewTheme = true;
      //     jQuery('.theme_participants_input').select2();
      //   },
      //   closeCreateThemeModal()
      //   {
      //     this.createNewTheme = false;
      //   },
      //   createNewThemeFunc: function (event) {
      //     axios.post('/chat/create_thread/',  {
      //       'name' : new FormData(event.target).get('name')
      //     }).then( response => {
      //       // this.threadsData = response.data
      //       this.newThread = response.data.new_thread_id
      //       let participantIds;
      //       participantIds = jQuery('.theme_participants_input').find(':selected').map((itemId, element) => {
      //           return element.value
      //       })
      //       // participantIds = jQuery('.theme_participants_input').find(':selected')
      //       // participantIds = jQuery('.theme_participants_input').find(':selected').data('');
      //       participantIds = participantIds.toArray()
      //       axios.post('/chat/invite_to_thread/',  {
      //         'thread_id' : this.newThread,
      //         'participants_id' : participantIds
      //       }).then( response => {
      //         // console.log(new FormData(event.target).get('participants_id'))
      //         console.log(response.data)
      //         this.threadsData = response.data
      //       })
      //       this.newThread = null
      //       this.createNewTheme = false;
      //     })
      //
      //   },
      //   sendImage: function()
      //   {
      //     // console.log(document.querySelector('.chat-footer'));
      //     let formData = new FormData(document.querySelector('.chat-footer'));
      //     if (formData.get('file').size !== 0) {
      //       var reader = new FileReader();
      //       reader.readAsDataURL(formData.get('file'));
      //       reader.onload = function () {
      //         console.log(reader.result);
      //         axios.post('/chat/send_message_to_thread/', {
      //           'image': reader.result,
      //           'thread_id': formData.get('threadId'),
      //           // 'file' : 'sdf'
      //         }).then(response => {
      //           // console.log(response.data)
      //           // this.threadMessages = response.data
      //         })
      //       }
      //     }
      //     document.querySelector('.chat-footer').reset()
      //   }
      // }
    }

}


</script>
