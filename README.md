# libPersianText

**Persian (Farsi) text that actually reads right in Minecraft Bedrock — a PocketMine-MP 5 virion.**

Bedrock has no Arabic-script shaping and no right-to-left support, so Persian sent from a plugin shows up as
disconnected letters in reverse order. `Persian::fix()` shapes the letters (joined initial / medial / final
forms, including the لا ligature) and reorders every line right-to-left, while keeping `§` color codes, English
words, numbers and commands readable. It works for chat messages, titles, forms, scoreboards — any string.

```php
use ApexMine\libPersianText\Persian;

$player->sendMessage(Persian::fix("§aخوش آمدید! برای راهنما بزنید §e/help"));
```

[فارسی ↓](#فارسی)

---

## Install

**DEVirion (development):** put this repository's folder in your server's `virions/` folder and install
[DEVirion](https://poggit.pmmp.io/p/DEVirion).

**Composer:**

```json
{
    "repositories": [{ "type": "vcs", "url": "https://github.com/ApexMine/libPersianText" }],
    "require": { "apexmine/libpersiantext": "^1.0" }
}
```

**Copy into your plugin:** copy `src/ApexMine/libPersianText/` into your plugin's `src/` and, if you like, change
the namespace to your own so it can't clash with another plugin shipping it.

Requires PHP 8.1+ with `mbstring` (both come with PocketMine-MP 5). There are no PocketMine dependencies, so the
engine also works outside a server.

## Usage

| Method | What it does |
|---|---|
| `Persian::fix(string $text, int $maxLineLength = 0) : string` | Shapes and reorders the text. Text without Persian letters is returned unchanged. |
| `Persian::fixAll(array $lines, int $maxLineLength = 0) : array` | `fix()` on every string in a list. |
| `Persian::wrap(string $text, int $maxLength) : string` | Only the line wrapping, on logical (not yet fixed) text. |
| `Persian::setEnabled(bool $enabled) : void` | Global switch, e.g. when your plugin's language is set to English. |

Results are cached (up to 1024 strings), so calling `fix()` on the same text every tick is cheap.

### Long lines: use `$maxLineLength`

When the client wraps a long right-to-left line itself, the pieces end up in the wrong order and the text has to be
read bottom-to-top. Pass a maximum line length and `fix()` splits long lines at word boundaries first:

```php
$form->setContent(Persian::fix($longText, 40)); // ~40 suits forms; chat can take more
```

Each new line keeps the active color, and text inside `()`, `[]`, `{}` or a run of English words such as
`/loan PlayerName` is never split.

## Tips

- **Fix once, at the end.** Build the full string first, then call `fix()`. If you fix a word (a status, a tag)
  and then insert it into another string that you fix again, that word gets reversed twice.
- **The first word of a line is shown on the right.** To show a command on the left with its description on the
  right, write the description first: `"Open the shop : /shop"`.
- **Don't wrap English arguments in `<>`.** `"/loan <player>"` displays as `<player> /loan`, because an angle-bracket
  group is kept as its own block. Write `"/loan PlayerName"` instead.
- **Give each part its own color.** A part without a color code of its own (a number after a colored `[tag]`,
  for example) takes the color of whatever is drawn before it. Start lines with a color and use `§f` where you
  want plain white.
- **Use the half-space (ZWNJ)** in words like می‌شود. It separates the letters correctly and isn't drawn in game.
- **Use Persian ی and ک, not Arabic ي and ك**, and English digits. Write the word «درصد» instead of `٪`, which
  some fonts don't have.
- **If Persian shows as boxes only inside `ModalForm`s,** your resource pack probably doesn't style modal forms.
  Use a `SimpleForm` with two buttons for confirmations instead.

## License

[MIT](LICENSE)

---

<div dir="rtl">

## فارسی

**کتابخانه (virion) برای نمایش درست متن فارسی در ماینکرفت بدراک، مخصوص PocketMine-MP 5.**

ماینکرفت بدراک از چسباندن حروف فارسی و راست‌به‌چپ پشتیبانی نمی‌کند؛ برای همین متن فارسی که یک پلاگین می‌فرستد با
حروف جدا از هم و برعکس نمایش داده می‌شود. `Persian::fix()` حروف را به شکل درست به هم می‌چسباند (شکل اول، وسط و آخر
کلمه و «لا») و هر خط را راست‌به‌چپ مرتب می‌کند. کدهای رنگ `§`، کلمه‌های انگلیسی، عددها و دستورها هم خوانا و سر جایشان
می‌مانند. برای پیام چت، تایتل، فرم، اسکوربورد و هر متن دیگری کار می‌کند.

<div dir="ltr">

```php
use ApexMine\libPersianText\Persian;

$player->sendMessage(Persian::fix("§aخوش آمدید! برای راهنما بزنید §e/help"));
```

</div>

### نصب

- **DEVirion (برای توسعه):** پوشه همین ریپو را در پوشه `virions/` سرور بگذارید و پلاگین
  [DEVirion](https://poggit.pmmp.io/p/DEVirion) را نصب کنید.
- **Composer:** ریپو را به‌عنوان `vcs` اضافه کنید و `apexmine/libpersiantext` را require کنید (نمونه در بخش انگلیسی بالا).
- **کپی داخل پلاگین:** پوشه `src/ApexMine/libPersianText/` را در `src/` پلاگین خودتان کپی کنید. بهتر است namespace را
  به namespace پلاگین خودتان تغییر دهید تا با پلاگین دیگری که همین کتابخانه را دارد تداخل نکند.

نیاز به PHP 8.1 به بالا و `mbstring` دارد که هر دو همراه PocketMine-MP 5 هستند. به PocketMine وابسته نیست.

### متدها

| متد | کار |
|---|---|
| `Persian::fix($text, $maxLineLength = 0)` | متن را شکل‌دهی و راست‌به‌چپ می‌کند. متن بدون حرف فارسی دست نمی‌خورد. |
| `Persian::fixAll($lines, $maxLineLength = 0)` | `fix()` روی همه متن‌های یک آرایه. |
| `Persian::wrap($text, $maxLength)` | فقط شکستن خط‌های بلند، روی متن اصلی (قبل از fix). |
| `Persian::setEnabled($enabled)` | خاموش و روشن کردن کلی، مثلا وقتی زبان پلاگین انگلیسی است. |

نتیجه‌ها کش می‌شوند (تا 1024 متن)، پس صدا زدن `fix()` روی یک متن ثابت در هر تیک هزینه‌ای ندارد.

### خط‌های بلند

اگر خط راست‌به‌چپ بلند باشد و خود بازی آن را بشکند، تکه‌ها جابه‌جا می‌شوند و متن را باید از پایین به بالا خواند. با
دادن حداکثر طول خط، `fix()` اول خط‌های بلند را از بین کلمه‌ها می‌شکند:

<div dir="ltr">

```php
$form->setContent(Persian::fix($longText, 40)); // حدود 40 برای فرم مناسب است
```

</div>

رنگ در خط جدید ادامه پیدا می‌کند و متن داخل پرانتز و براکت و دستورهای انگلیسی مثل `/loan PlayerName` نصفه نمی‌شوند.

### نکته‌ها

- **فقط یک بار و آخر کار fix کنید.** اول کل متن را بسازید، بعد `fix()` بزنید. اگر یک کلمه را fix کنید و بعد داخل
  متن دیگری بگذارید و دوباره fix کنید، آن کلمه دو بار برعکس می‌شود.
- **اولین کلمه هر خط سمت راست نمایش داده می‌شود.** برای اینکه دستور سمت چپ و توضیحش سمت راست باشد، اول توضیح را
  بنویسید: `"باز کردن فروشگاه : /shop"`.
- **آرگومان انگلیسی را داخل `<>` نگذارید.** `"/loan <player>"` به شکل `<player> /loan` نمایش داده می‌شود، چون `<...>`
  یک تکه جدا حساب می‌شود. به‌جایش بنویسید `"/loan PlayerName"`.
- **به هر بخش رنگ خودش را بدهید.** بخشی که کد رنگ ندارد (مثلا عددی بعد از یک `[تگ]` رنگی) رنگ بخش قبلی را می‌گیرد.
  خط‌ها را با رنگ شروع کنید و هرجا سفید ساده می‌خواهید `§f` بگذارید.
- **از نیم‌فاصله استفاده کنید** (می‌شود، درخواست‌ها). حروف را درست جدا می‌کند و در بازی دیده نمی‌شود.
- **از ی و ک فارسی استفاده کنید، نه ي و ك عربی،** و عدد انگلیسی بنویسید. به‌جای `٪` کلمه «درصد» بنویسید، چون بعضی
  فونت‌ها آن را ندارند.
- **اگر فارسی فقط داخل `ModalForm` به شکل مربع نمایش داده می‌شود،** احتمالا ریسورس‌پک شما روی فرم مودال اعمال
  نمی‌شود. برای تایید از `SimpleForm` با دو دکمه استفاده کنید.

### لایسنس

[MIT](LICENSE)

</div>
