import type { BgaAutofit as BgaAutofitLib } from "../../bga-autofit";

const BgaAutofit: typeof BgaAutofitLib = await globalThis.importEsmLib('bga-autofit', '1.x');

export { BgaAutofit };
