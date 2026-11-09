# Auto-pick and map actions

When adding or changing a map, expansion, power, or action, check whether it must reset auto-pick for all players.

Call `Game::resetAutoPick()` when an action successfully commits a change displayed on the map. This includes claiming a route, building a station, placing a city marker (including a starting city), laying a trackbed piece, and moving Alvin in Old West. Confirmed powers that change the map must also reset auto-pick; a successful route claim already covers powers used to claim that route.

Reset only after the action is validated and the map change is committed. A tunnel resets auto-pick only when it is successfully built, through `applyClaimRoute()`. Failed, declined, or passed tunnel attempts must not reset auto-pick.

Do not reset auto-pick for drawing Train Car cards or Destination cards. Do not reset it for previews, selections, or cancellable power activations, such as activating Phileas Fogg before confirming a route claim, or cancelling such an activation.

Use the shared `resetAutoPick()` helper instead of duplicating counter updates. It checks `getMax() > 0` before calling `setAll(0)`, avoiding unnecessary reset notifications. Put the call in the shared method that commits the map change so normal actions, powers, and zombie actions follow the same rule.

Ask the user when it is unclear whether a new action or confirmed power should reset auto-pick.
