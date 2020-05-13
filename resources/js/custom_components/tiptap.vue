<template>
    <div :class="wrapperClass">
        <editor-menu-bar :editor="editor" v-slot="{commands, isActive }">
            <div class="menubar">

                <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.bold() }"
                        @click.prevent="commands.bold"
                >
                    <icon name="bold"/>
                </button>

                <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.italic() }"
                        @click.prevent="commands.italic"
                >
                    <icon name="italic"/>
                </button>

                <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.strike() }"
                        @click.prevent="commands.strike"
                >
                    <icon name="strike"/>
                </button>

                <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.underline() }"
                        @click.prevent="commands.underline"
                >
                    <icon name="underline"/>
                </button>

                <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.code() }"
                        @click.prevent="commands.code"
                >
                    <icon name="code"/>
                </button>

                <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.paragraph() }"
                        @click.prevent="commands.paragraph"
                >
                    <icon name="paragraph"/>
                </button>

                <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.heading({ level: 1 }) }"
                        @click.prevent="commands.heading({ level: 1 })"
                >
                    H1
                </button>

                <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.heading({ level: 2 }) }"
                        @click.prevent="commands.heading({ level: 2 })"
                >
                    H2
                </button>

                <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.heading({ level: 3 }) }"
                        @click.prevent="commands.heading({ level: 3 })"
                >
                    H3
                </button>

                <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.bullet_list() }"
                        @click.prevent="commands.bullet_list"
                >
                    <icon name="ul"/>
                </button>

                <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.ordered_list() }"
                        @click.prevent="commands.ordered_list"
                >
                    <icon name="ol"/>
                </button>

                <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.blockquote() }"
                        @click.prevent="commands.blockquote"
                >
                    <icon name="quote"/>
                </button>

                <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.code_block() }"
                        @click.prevent="commands.code_block"
                >
                    <icon name="code"/>
                </button>

                <button
                        class="menubar__button"
                        @click.prevent="commands.horizontal_rule"
                >
                    <icon name="hr"/>
                </button>

                <button
                        class="menubar__button"
                        @click.prevent="commands.undo"
                >
                    <icon name="undo"/>
                </button>

                <button
                        class="menubar__button"
                        @click.prevent="commands.redo"
                >
                    <icon name="redo"/>
                </button>

            </div>
        </editor-menu-bar>
        <editor-content :editor="editor" class="ml-2 pa-2 bdr bdr-rnd-5 w-100 h-30-rem" />
    </div>
</template>

<script>
  import {Editor, EditorContent, EditorMenuBar} from 'tiptap';
  import {
    Blockquote,
    CodeBlock,
    HardBreak,
    Heading,
    HorizontalRule,
    OrderedList,
    BulletList,
    ListItem,
    TodoItem,
    TodoList,
    Bold,
    Code,
    Italic,
    Link,
    Strike,
    Underline,
    History,
  } from 'tiptap-extensions'
  import Icon from './icon';

  export default {
    name: "tiptap",

    components: {
      EditorContent,
      EditorMenuBar,
      Icon
    },

    props: {
      'content': String
    },

    data() {
      return {
        wrapperClass: [
          'pa-3',
          'v-input',
          'v-textarea',
          'v-input--is-label-active',
          'v-input--is-dirty theme--light',
          'v-text-field v-text-field--is-booted',
          'v-text-field--enclosed',
          'v-text-field--outlined'
        ].join(' '),
        editor: new Editor({
          content: this.content,

          extensions: [
            new Blockquote(),
            new BulletList(),
            new CodeBlock(),
            new HardBreak(),
            new Heading({levels: [1, 2, 3]}),
            new HorizontalRule(),
            new ListItem(),
            new OrderedList(),
            new TodoItem(),
            new TodoList(),
            new Link(),
            new Bold(),
            new Code(),
            new Italic(),
            new Strike(),
            new Underline(),
            new History(),
          ],

          onUpdate: ({ getHTML }) => {
            this.$emit('description-changed', getHTML());
          }
        }),
      }
    },

    beforeDestroy() {
      this.editor.destroy();
    }
  }
</script>

<style lang="scss">
 @import "../../sass/tiptap.scss";
</style>