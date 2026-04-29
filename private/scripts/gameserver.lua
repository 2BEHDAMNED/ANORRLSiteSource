-- anorrrlll gameserver scriper
-- ok so the reason why it sooo bogged down is because this script is only EVER used for studio.
-- yeah no where else.

-- Start Game Script Arguments
local placeId, port, sleeptime, timeout, injectScriptAssetID, libraryRegistrationScriptAssetID = ...

-----------------------------------"CUSTOM" SHARED CODE----------------------------------

pcall(function() settings().Network.UseInstancePacketCache = true end)
pcall(function() settings().Network.UsePhysicsPacketCache = true end)
pcall(function() settings()["Task Scheduler"].PriorityMethod = Enum.PriorityMethod.AccumulatedError end)
settings().Network.PhysicsSend = Enum.PhysicsSendMethod.TopNErrors
settings().Network.ExperimentalPhysicsEnabled = true
settings().Network.WaitingForCharacterLogRate = 100
pcall(function() settings().Diagnostics:LegacyScriptMode() end)

local scriptContext = game:GetService('ScriptContext')
pcall(function() scriptContext:AddStarterScript(libraryRegistrationScriptAssetID) end)
scriptContext.ScriptsDisabled = true

game:SetPlaceID(placeId, false)
game:GetService("ChangeHistoryService"):SetEnabled(false)

-- establish this peer as the Server
local ns = game:GetService("NetworkServer")

pcall(function() game:GetService("NetworkServer"):SetIsPlayerAuthenticationRequired(false) end)
settings().Diagnostics.LuaRamLimit = 0

game:GetService("Players").PlayerAdded:Connect(function(player)
	print("Player ", player.userId, " added")
end)

game:GetService("Players").PlayerRemoving:Connect(function(player)
	print("Player ", player.userId, " leaving")
end)

-- Now start the connection
ns:Start(port, sleeptime) 

if timeout then
	scriptContext:SetTimeout(timeout)
end
scriptContext.ScriptsDisabled = false

-- StartGame --
if injectScriptAssetID and (injectScriptAssetID < 0) then
	pcall(function() game:LoadGame(injectScriptAssetID * -1) end)
else
	pcall(function() game:GetService("ScriptContext"):AddStarterScript(injectScriptAssetID) end)
end

game:GetService("RunService"):Run()