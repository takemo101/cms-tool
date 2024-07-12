// Definition of MasterCSS custom style

type RGB = `rgb(${number},${number},${number})`;
type HEX = `#${string}`;

type StyleColor = RGB | HEX;

export const Colors = {
  Background: "#f8f9fd",
  Primary: "#563bff",
  Secondary: "#fc783f",
  Tertiary: "#21213b",
  Warning: "#ff5a5a",
  Link: "#7979b4",
  ActiveLinkBackground: "#e9e7fd",
  ActiveLink: "#563bff",
  Success: "#00b09b",
  Error: "#ff5a5a",
} as const satisfies Record<string, StyleColor>;

const generalStyle = {
  // layout styles
  body: `f:sans-serif_* m:0`,
  layout: `
    min-h:100vh
    p:0 m:0
    bg:${Colors.Background}
  `,
  "layout--center": `
    h:100vh@sm
    h:full
    py:0@sm py:60px
  `,
  container: `
    flex flex:col
    mx:auto
    h:full
    px:20px@sm px:8px
  `,

  // alert styles
  alert: `
    flex jc:normal ai:center
    py:10 px:14
    f:14px
    r:5px
    b:2|solid
  `,
  alert__title: `f:18 f:bold m:0 mb:5px`,
  alert__close: `w:30 pl:10`,
  "alert__close-icon": `cursor:pointer opacity:0.5:hover`,

  "alert--error": `bg:red-90 b:red`,
  "alert--error-text": `f:red`,

  "alert--info": `bg:${Colors.Background} b:${Colors.Tertiary}`,
  "alert--info-text": `f:${Colors.Tertiary}`,

  // form styles
  "form-column": `flex flex:col my:1rem`,
  "form-label": `f:14px f:bold mb:0.5rem`,
  "form-input": `
    b:1px|solid|gray-82
    outline-color:${Colors.Primary}
    px:10px py:10px r:5px
    appearance:none
  `,
  "form-input--textarea": `min-w:full max-w:full`,
  "form-color": `
    h:40px w:full
    b:1px|solid|gray-82
    outline-color:${Colors.Primary}
    p:3px r:5px
    cursor:pointer
    appearance:none
  `,
  "form-select": `
    b:1px|solid|gray-82
    px:10px r:5px h:41px lh:41px
    f:13px
    block
    outline-color:${Colors.Primary}
    appearance:none
    bg:url(/vendor/assets/arrow.png) bg:18 bg:no-repeat bg:center|right bg:transparent
  `,
  "form-checkbox": `
    mb:5px
    inline-flex
    ai:center

    appearance:none>input
    w:26px>input h:26px>input
    f:${Colors.Primary}>input
    outline-color:gray-82>input
    bg:gray-82>input
    bg:${Colors.Primary}>input:checked
    b:1px|solid|gray-82>input
    mr:8px>input
    r:5px>input

    f:bold>span
  `,
  "form-btn": `
    m:0;
    inline-block
    f:16px f:bold
    px:10px r:5px
    h:46px lh:46px
    cursor:default:disabled
    cursor:pointer
    opacity:0.5:hover
    opacity:0.8:disabled
  `,
  "form-btn--primary": `
    bg:${Colors.Primary}
    f:white
  `,
  "form-btn--primary-line": `
    bg:white
    b:1px|solid|${Colors.Primary}
    f:${Colors.Primary}
  `,
  "form-btn--secondary": `
    bg:${Colors.Secondary}
    f:white
  `,
  "form-btn--warning": `
    bg:${Colors.Warning}
    f:white
  `,

  "form-file": `flex`,
  "form-file__text": `
    overflow:hidden
    text:ellipsis
    white-space:nowrap
    w:3/5 h:41px lh:41px
    f:13px
    b:1px|solid|gray-82
    outline-color:${Colors.Primary}
    px:10px rl:5px
    box:border-box
  `,
  "form-file__btn": `
    w:1/5 h:41px lh:41px
    f:13px f:bold
    px:10px
    cursor:pointer
    opacity:0.5:hover
    box:border-box
  `,
  "form-file__btn--ref": `
    f:white
    bg:${Colors.Primary}
    outline-color:${Colors.Primary}
    b:none
  `,
  "form-file__btn--clear": `
    f:${Colors.Primary}
    bg:white
    outline-color:${Colors.Primary}
    b:1px|solid|${Colors.Primary}
  `,

  "form-prev": `
    block w:240 position:relative
  `,
  "form-prev__img": `
    block
    h:240px w:240px
    opacity:0.5:hove
    b:1px|solid|gray-82
    r:5px

    block>img
    h:240px>img w:240px>img
    opacity:0.5:hover>img
    obj:cover>img
  `,
  "form-prev__btn-del": `
    position:absolute
    right:8px top:8px
    b:1px|solid|${Colors.Primary}
    bg:white
    f:12px f:${Colors.Primary} f:bold
    r:5px
    px:10px py:6px
    cursor:pointer
    opacity:0.5:hover
  `,

  "form-required": `
    f:10px f:white
    bg:red
    r:4px
    p:4px ml:4px
  `,

  "form-hint": `
    d:block
    mt:4px
    f:12px f:gray-62
  `,

  // card styles
  card: `
    b:1|solid|gray-82
    r:5px
    w:full h:max"
    bg:white
    box:border
  `,
  "card-header": `px:20px@sm px:14px pt:20px@sm pt:14px`,
  "card-header__title": `mb:20px@sm mb:14px`,
  "card-header__title--layout": `flex ai:center`,
  "card-header__title__back": `
    mr:8px
    f:20px
    opacity:0.5:hover
  `,
  "card-body": `p:20px@sm p:14px`,

  // utility styles
  divider: `
    bx:0 bb:0 bt:1|solid|gray-82
    p:0 m:0 h:
  `,
  link: `
    f:${Colors.Link} f:bold
    cursor:pointer
    opacity:0.5:hover
    text:none
  `,
  "link-btn": `
    m:0;
    inline-block
    bg:${Colors.Primary}
    f:16px f:bold t:center
    py:0 px:10px r:5px
    h:46px lh:44px
    cursor:pointer
    opacity:0.5:hover
  `,
  "link-btn--primary": `
    f:white
    bg:${Colors.Primary}
    b:1|solid|${Colors.Primary}
  `,
  "link-btn--primary-line": `
    bg:white
    b:1|solid|${Colors.Primary}
    f:${Colors.Primary}
  `,
  "link-btn--secondary": `
    f:white
    bg:${Colors.Secondary}
    b:1|solid|${Colors.Secondary}
  `,

  // text utility styles
  "u-section": `mb:60px@sm mb:50px`,
  "u-title": `f:18px f:bold mt:0 mb:16px`,
  "u-text": `f:16 lh:1.6rem mt:0 mb:16px`,
  "u-text--gray": `f:gray-52`,
  "u-list": `
    f:16px list-style:circle
    pl:18px lh:1.6rem
    mb:10px>li
  `,
} as const;

const centerLayoutStyle = {
  // centering styles
  "center-card": `
    max-w:380px@sm
    mx:auto my:auto
    p:20px@sm
    p:14px
    b:1|solid|gray-82
    r:5px
    w:full h:max"
    bg:white
    box:border
  `,
  "center-card__title": `f:30px m:0`,
  "center-card__subtitle": `
    f:20px mt:10px lh:1.6rem
    f:gray-52
  `,
  "center-card__minititle": `
    f:15px mt:10px lh:1.4rem
    f:gray-52
  `,
} as const;

const headerNavStyle = {
  // header styles
  header: `
    w:full
    bg:white
    shadow:2|2|4|gray-80
    position:fixed
  `,
  header__inner: `
    max-w:1200px@sm w:full
    mx:auto
    flex flex:row jc:space-between
    px:20px@sm px:8px
    h:50px
    h:60px@sm
    f:sans-serif t:center
    ai:center
    box:border
  `,
  header__logo: `
    h:50px@sm w:50px@sm h:40px w:40px
    mr:8px
    as:center
  `,
  "header__logo-img": `h:full w:full as:center`,
  header__title: `f:20px f:bold as:center`,
  header__btn: `
    b:none
    bg:${Colors.Secondary}
    f:14px f:white f:bold
    px:10px h:32px
    r:5px
    cursor:pointer
    opacity:0.5:hover
  `,
} as const;

const halfColumnLayoutStyle = {
  // half column layout styles
  "half-column": `
    max-w:860px@sm w:full
    mx:auto
    mt:60px@sm mt:50px
    py:30px@sm py:20px
    flex flex:row@sm flex:col gap:20px
  `,
  "half-column__left": `flex-basis:220@sm flex-basis:full`,
  "half-column__right": `
    flex-basis:full
    break-word
  `,
} as const;

const customizationColumnLayoutStyle = {
  "full-column": `
    w:full h:100vh
    flex flex:row@sm flex:col-reverse
  `,
  "full-column__left": `
    h:100vh@sm h:45vh
    flex-basis:460px@md flex-basis:400@sm flex-basis:40%
  `,
  "full-column__right": `
    h:100vh@sm h:55vh
    flex-grow:1@sm flex-grow:0
    flex-basis:auto@sm flex-basis:60%
    flex
    jc:center
    p:20px@sm p:14px

    h:full>iframe
    bg:white>iframe
    r:5px>iframe
    b:1px|solid|gray-82>iframe
  `,
} as const;

const customizationPanelStyle = {
  "cstm-panel": `
    w:full h:full
    bg:white
    flex flex:col
    br:1px|solid|gray-82@sm br:none
    bt:none@sm bt:1px|solid|gray-82
  `,
  "cstm-panel__header": `
    flex-basis:70px
    flex flex:row
    px:20px@sm px:14px
    bb:1px|solid|gray-82@
    ai:center jc:space-between
  `,
  "cstm-panel__body": `
    flex-basis:full
    overflow-y:scroll
  `,
  "cstm-panel__body__inner": `
    p:20px@sm p:14px
  `,
  "cstm-panel__header__group": `
    flex flex:row gap:14px
    h:42px
  `,
  "device-switch": `
    flex flex:row
    r:5px
    bg:gray-82 p:6px h:full
  `,
  "device-switch__btn": `
    inline-block
    w:40px h:30px
    lh:30px t:center
    r:5px
    cursor:pointer

    bg:white.active
    f:black.active>i

    f:white.inactive>i
  `,
  "cstm-save-btn": `
    h:42px w:80px
    lh:42px f:bold
    r:5px

    bg:${Colors.Primary}.active
    f:white.active
    cursor:pointer.active
    opacity:0.5:hover.active

    f:${Colors.Secondary}.inactive>i
    cursor:default.inactive
    opacity:0.5.inactive
  `,
  "cstm-title": `
    mb:8px bb:1|solid|gray-82
  `,
  "cstm-title__inner": `
    rel
    lh:2
    cursor:pointer
    opacity:0.5:hover
  `,
  "cstm-title__text": `:18px`,
  "cstm-title__icon": `
    abs
    right:0 top:0
  `,
} as const;

const dialogStyle = {
  dialog: `
    p:0
    r:5px
    bg:white
    b:1|solid|gray-82
    w:fit-content@sm w:full
  `,
  "dialog-layout": `
    flex:col jc:center
    w:360px@sm w:full
    px:20px pb:20px pt:10px
    t:left
  `,
  "dialog-header": `
    flex jc:space-between
    w:full
    lh:2.8
    bb:1|solid|gray-82
  `,
  "dialog-header__title": `f:20 f:bold`,
  "dialog-header__close": `
    cursor:pointer
    opacity:0.5:hover
  `,
  "dialog-body": `w:full py:30`,
  "dialog-body__message": `f:gray-52`,
  "dialog-footer": `
    w:full
    flex jc:space-between
  `,
} as const;

const sidebarNavStyle = {
  // sidebar styles
  sidebar: `overflow:hidden@sm overflow-x:scroll`,
  sidebar__inner: `
    min-w:max-content
    flex flex:col@sm flex:row
  `,

  // nav styles
  "nav-link": `
    f:14px f:bold
    f:${Colors.Link}
    px:8px py:12px
    cursor:pointer
    opacity:0.5:hover
    text:none
  `,
  "nav-link--active": `
    bl:2px|solid|${Colors.Link}@sm bl:0
    bg:${Colors.ActiveLinkBackground}
    f:${Colors.ActiveLink}
  `,
} as const;

const themeStyle = {
  "theme-list": `
    grid-cols:2@sm flex flex:col
    gap:20px@sm gap:30px
  `,
  "theme-item": `flex:row m:0`,
  "theme-item__title": `
    m:0
    f:bold f:16px
    cursor:pointer
    opacity:0.5:hover
    text:none
  `,
  "theme-tags": `inline-flex gap:10px`,
  "theme-tags__item": `
    f:14px f:${Colors.Primary} f:bold
    b:1|solid|${Colors.Primary}
    r:4px
    py:6px px:10px
  `,
  "theme-item__thumb": `
    block
    opacity:0.5:hover
    p:0 mb:10px
    position:relative
  `,
  "theme-item__thumb-active": `
    position:absolute top:8px right:8px
    block
    f:14px f:white f:bold
    bg:${Colors.Primary}
    r:4px
    py:6px px:10px
  `,
  "theme-item__thumb-img": `
    block
    w:full
    h:300px
    obj:cover
    m:0 r:5px
  `,
} as const;

const tableStyle = {
  "table-container": `
    overflow-x:auto@sm overflow-x:scroll mb:16px
  `,
  table: `
    border:separate border-spacing:0
    w:full@sm w:max-content

    f:bold>thead>tr
    b:1|solid|gray-82>thead>tr>th
    p:10>thead>tr>th
    bl:0>thead>tr>th:last-child

    p:10>tbody>tr>td
    b:1|solid|gray-82>tbody>tr>td
    bt:0>tbody>tr>td
    bl:0>tbody>tr>td:last-child
  `,
} as const;

const menuStyle = {
  menu: `
    abs z:10
    min-w:180px
    overflow:auto
    bg:white
    r:5px
    top:calc(100%+10px)
    shadow:1|1|6|gray-80
  `,
  "menu--left": `right:auto left:0`,
  "menu--right": `left:auto right:0`,
  "menu--center": `left:50% transform:translateX(-50%)`,
  "menu-link": `
    block
    w:full p:10px
    f:${Colors.Link} t:left t:16px
    cursor:pointer
    bg:gray-82:hover
    text:none
  `,
} as const;

const style = {
  ...generalStyle,
  ...centerLayoutStyle,
  ...headerNavStyle,
  ...halfColumnLayoutStyle,
  ...customizationColumnLayoutStyle,
  ...customizationPanelStyle,
  ...dialogStyle,
  ...sidebarNavStyle,
  ...themeStyle,
  ...tableStyle,
  ...menuStyle,
} as const;

export default style;
