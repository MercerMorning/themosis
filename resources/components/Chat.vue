<template>
  <div class="chat-container">
    <div class="menu-threads">
      <div class="menu-threads__current-user">
        <a class="menu-threads__link current-user">
          <img class="thread-link__participant_ava" v-bind:src="currentUserData.ava">
          <div class="thread-link__current-user_name">
            {{ currentUserData.first_name + ' ' + currentUserData.last_name }}
          </div>
        </a>
      </div>
      <ul class="menu-threads__list" v-if="addingChat === 'group'">
        <form v-on:submit.prevent="createGroupThread">
          <span class="creat-group_thread__title">Создание группового чата</span>
          <div class="create-group_thread__input-input_container">
            <svg width="75" height="75">
              <use
                  xlink:href="/content/themes/myinvision/assets/images/open_adding_group_thread_modal.svg#add_group"></use>
            </svg>
            <div class="create_group_chat">
              <label class="create_group-thread__input-text">
                <input class="input-message" type="text" name="thread_name" placeholder="Название группы" required>
              </label>

              <label class="chat-footer__send-message">
                <button class="button chat-footer__button" type="submit">
                            <span class="send-button_text">
                                Создать
                            </span>
                </button>
              </label>
            </div>
          </div>

          <ul>
            <li class="menu-threads__item" v-for="user in usersData" v-on:click="addParticipant"
                v-bind:data-id="user.id">
              <a class="menu-threads__link">
                <img class="thread-link__participant_ava" v-bind:src="user.ava">
                <div class="thread-link__dialog">
                  <div class="thread-link__content">
                          <span class="thread-participant_name">
                            {{ user.first_name + ' ' + user.last_name }}
                          </span>
                  </div>
                </div>
                <svg class="add_to_thread" width="25" height="25">
                  <use xlink:href="/content/themes/myinvision/assets/images/add.svg#add"></use>
                </svg>
              </a>
            </li>
          </ul>
        </form>
      </ul>
      <ul class="menu-threads__list" v-else-if="addingChat === 'private'">
        <li class="menu-threads__item" v-for="user in privateUsersData" v-on:click="openOrCreatePrivateThread"
            v-bind:data-id="user.id">
          <a class="menu-threads__link">
            <img class="thread-link__participant_ava" v-bind:src="user.ava">
            <div class="thread-link__dialog">
              <div class="thread-link__content">
                          <span class="thread-participant_name">
                            {{ user.first_name + ' ' + user.last_name }}
                          </span>
              </div>
            </div>
            <svg class="add_to_thread" width="25" height="25">
              <use xlink:href="/content/themes/myinvision/assets/images/privateMsg#privateMsg"></use>
            </svg>
          </a>
        </li>
      </ul>
      <ul v-else class="menu-threads__list">

        <li v-for="thread in threadsData"
            v-on:click="showThread"
            v-bind:data-id="thread.id"
            v-bind:class="[thread.id === currentThread.id
               ? 'menu-threads__item active'
               : 'menu-threads__item']">
          <a class="menu-threads__link">

            <img v-if="thread.is_private" class="thread-link__participant_ava"
                 v-bind:src="thread.ava">
            <div v-else class="thread-link__participant_ava"></div>
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

      <ul class="menu-threads__adding_chat">
        <li class="add_chat_item">
          <svg width="20" height="18">
            <use xlink:href="/content/themes/myinvision/assets/images/add_group_thread.svg#add_thread"></use>
          </svg>
          <a class="add_chat_item__make_chat_link" v-on:click.prevent="openGroupChatAdd">
            Создать групповой чат
          </a>
        </li>
        <li class="add_chat_item">
          <svg width="20" height="18">
            <use xlink:href="/content/themes/myinvision/assets/images/add_group_thread.svg#add_thread"></use>
          </svg>
          <a class="add_chat_item__make_chat_link" v-on:click.prevent="openCreatingPrivateThread">
            Создать личный чат
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

        <div v-for="(groupMessages, date) in threadMessages">
          <div class="chat-message__date">
                <span>
                    {{ date }}
                </span>
          </div>
          <div v-for="groupMessage in groupMessages"
               v-bind:class="[groupMessage.user_info.id == currentUserData.id
               ? 'chat-message__message chat-message__message_current-user'
               : 'chat-message__message chat-message__message_foreign-user']">
            <span v-if="groupMessage.user_info.id !== currentUserData.id" class="chat-message__autor">
              {{ groupMessage.user_info.first_name + ' ' + groupMessage.user_info.last_name }}
            </span>
            <div class="chat-message__message_message-content">
              <img class="thread-link__participant_ava"
                   v-bind:src="groupMessage.user_info.ava">
              <div class="chat-message__user-messages">
                <div v-for="message in groupMessage.messages" class="chat-message__message_message-body">
                  <div class="chat-message__text">
                    <img class="message_image" v-if="message.isFile" v-bind:src="message.body">
                    <span v-else>{{ message.body }}</span>

                  </div>
                  <div class="chat-message__time">{{ message.datetime }}</div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
      <form class="chat-footer" v-on:submit.prevent="sendMessage">
        <input type="hidden" name="threadId" v-bind:value="currentThread.id">
        <div class="chat-footer__input-wrpa">
          <label class="chat-footer__input-file">
            <svg width="20" height="18">
              <use xlink:href="/content/themes/myinvision/assets//images/sprite.svg#icon-clip"></use>
            </svg>
            <input type="file"  accept="image/png, image/jpeg"  name="file" v-on:change="sendImage">
          </label>
          <label class="chat-footer__input-text">
            <input class="input-message" type="text" name="body" placeholder="Введите сообщение">
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
  mounted: function () {
    jQuery(".chat-body").animate({scrollTop: 1000000000}, "slow");
    const socket = new WebSocket("ws://localhost:8999");

    // socket.onopen = () => {
    //   socket.send("Hello!");
    // };

    socket.onmessage = (response) => {

      let parsedResponse = JSON.parse(response.data);
      // if (parsedResponse.currentThread.id === this.currentThread.id) {
      this.threadMessages = parsedResponse.threadMessages;
      // }
      // this.threadsData = parsedResponse.threads
      // this.currentThread = parsedResponse.currentThread;
      // this.threadMessages = parsedResponse.threadMessages;
    };
  },
  props: {
    threads: String,
    currentuser: String,
    currentthread: String,
    threadmessages: String,
    usertoken: String,
    users: String,
    privateusers: String,
  },
  data: function () {
    {
      return {
        socket: new WebSocket("ws://localhost:8999"),
        threadsData: JSON.parse(this.threads),
        currentUserData: JSON.parse(this.currentuser),
        currentThread: JSON.parse(this.currentthread) ?? {id: 0},
        threadMessages: JSON.parse(this.threadmessages) ?? null,
        userToken: JSON.parse(this.usertoken) ?? null,
        addingChat: null,
        usersData: JSON.parse(this.users),
        privateUsersData: JSON.parse(this.privateusers)
      }
    }
  },
  methods: {
    showThread: function (e) {
      let idItem = event.target.closest('.menu-threads__item');
      axios.get('/chat/get_thread?id=' + idItem.dataset.id).then(response => {
        document.cookie = "currentThreadId=" + response.data.currentThread.id;
        this.threadsData = response.data.threads
        this.currentThread = response.data.currentThread;
        this.threadMessages = response.data.threadMessages;
      })
    },
    sendMessage: function (event) {
      jQuery(".chat-body").animate({scrollTop: 1000000000}, "slow");
      if (this.currentThread.id !== 0) {
        let formData = new FormData(event.target);
        this.socket.send(JSON.stringify({
          user_id: this.currentUserData.id,
          thread_id: formData.get('threadId'),
          body: formData.get('body'),
          token: this.userToken,
        }));
        document.querySelector('.chat-footer').reset();
      }

    },
    sendImage: function () {
      jQuery(".chat-body").animate({scrollTop: 1000000000}, "slow");
      let formData = new FormData(document.querySelector('.chat-footer'));
      if (formData.get('file').size !== 0) {
        var reader = new FileReader();
        reader.readAsDataURL(formData.get('file'));
        reader.onload = () => {
          this.socket.send(JSON.stringify({
            user_id: this.currentUserData.id,
            thread_id: formData.get('threadId'),
            file: reader.result,
            token: this.userToken,
            body: ' '
          }));
          document.querySelector('.chat-footer').reset();
        }
      }

      document.querySelector('.chat-footer').reset()
    },
    openGroupChatAdd: function () {
      this.addingChat = 'group';
    },
    addParticipant: function (e) {
      let idItem = e.target.closest('.menu-threads__item');
      idItem.classList.toggle('active');

      // axios.get('/chat/get_thread?id=' +  idItem.dataset.id).then( response => {
      //   document.cookie = "currentThreadId=" + response.data.currentThread.id;
      //   this.threadsData = response.data.threads
      //   this.currentThread = response.data.currentThread;
      //   this.threadMessages = response.data.threadMessages;
      // })
    },
    createGroupThread: function (e) {
      let threadName = new FormData(e.target).get('thread_name');
      let participants = e.target.querySelectorAll('.active')
      participants = Array.from(participants).map(elem => {
        return elem.dataset.id;
      })
      axios.post('/chat/create_group_thread/', {
        'subject': threadName,
        'recipients': participants,
      }).then(response => {
        this.addingChat = null;
        this.threadsData = response.data.threads;
      })
    },
    openCreatingPrivateThread: function (e) {
      this.addingChat = 'private';
    },
    openOrCreatePrivateThread: function (e) {
      let userId = e.target.closest('.menu-threads__item');
      console.log(e.target)
      axios.post('/chat/create_private_thread/', {
        'recipient': userId.dataset.id,
      }).then(response => {
        console.log(response.data.threads);
        this.threadsData = response.data.threads;
        this.privateUsersData = response.data.privateUsers;
        // this.currentThread = response.data.currentThread;
      })
      this.addingChat = null;
    }
  }

}


</script>
